<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PeminjamanResource;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PeminjamanApiController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/v1/peminjamans (Wajib Bearer Token)
     * Mengambil daftar transaksi peminjaman buku terpaginasi dengan filter NIM, status, dan pencarian.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 10), 1), 50);

        $paginator = Peminjaman::query()
            ->with('buku')
            ->when($request->query('status'), function ($q, $status) {
                $q->where('status', strtolower($status));
            })
            ->when($request->query('nim'), function ($q, $nim) {
                $q->where('nim_peminjam', strtoupper(trim($nim)));
            })
            ->when($request->query('buku_id'), function ($q, $bukuId) {
                $q->where('buku_id', $bukuId);
            })
            ->when($request->query('search'), function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('kode_pinjam', 'like', "%{$search}%")
                        ->orWhere('nim_peminjam', 'like', "%{$search}%")
                        ->orWhere('nama_peminjam', 'like', "%{$search}%")
                        ->orWhereHas('buku', function ($bq) use ($search) {
                            $bq->where('judul', 'like', "%{$search}%")
                                ->orWhere('isbn', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return $this->successResponse(
            data: PeminjamanResource::collection($paginator->items()),
            message: 'Daftar riwayat transaksi peminjaman berhasil diambil.',
            meta: [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total_items' => $paginator->total(),
                'total_pages' => $paginator->lastPage(),
                'links' => [
                    'first' => $paginator->url(1),
                    'prev' => $paginator->previousPageUrl(),
                    'next' => $paginator->nextPageUrl(),
                    'last' => $paginator->url($paginator->lastPage()),
                ],
            ]
        );
    }

    /**
     * GET /api/v1/peminjamans/{id} (Wajib Bearer Token)
     * Mengambil detail satu transaksi peminjaman buku berdasarkan ID atau kode_pinjam.
     */
    public function show(string $id): JsonResponse
    {
        $peminjaman = Peminjaman::with('buku')
            ->where('id', $id)
            ->orWhere('kode_pinjam', $id)
            ->first();

        if (!$peminjaman) {
            return $this->problemResponse(
                title: 'Resource Not Found',
                detail: "Transaksi peminjaman dengan ID atau kode '{$id}' tidak ditemukan.",
                status: 404,
                errorSlug: 'loan-not-found'
            );
        }

        return $this->successResponse(
            data: new PeminjamanResource($peminjaman),
            message: 'Detail transaksi peminjaman berhasil ditemukan.'
        );
    }

    /**
     * POST /api/v1/peminjamans (Wajib Bearer Token)
     * Membuat transaksi peminjaman baru berbasis ACID & Pessimistic Locking (lockForUpdate).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'nim_peminjam' => 'required|string|min:6|max:20',
            'nama_peminjam' => 'required|string|min:3|max:150',
            'tanggal_pinjam' => 'nullable|date',
            'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_pinjam',
        ], [
            'buku_id.required' => 'ID Buku wajib dipilih.',
            'buku_id.exists' => 'Buku yang dipilih tidak terdaftar di katalog perpustakaan.',
            'nim_peminjam.required' => 'NIM peminjam wajib diisi.',
            'nama_peminjam.required' => 'Nama lengkap peminjam wajib diisi.',
            'tanggal_jatuh_tempo.after_or_equal' => 'Tanggal jatuh tempo tidak boleh mendahului tanggal pinjam.',
        ]);

        $tglPinjam = $validated['tanggal_pinjam'] ?? now()->toDateString();
        $tglJatuhTempo = $validated['tanggal_jatuh_tempo'] ?? now()->addDays(7)->toDateString();

        try {
            $peminjaman = DB::transaction(function () use ($validated, $tglPinjam, $tglJatuhTempo) {
                // 1. Kunci baris buku menggunakan pessimistic locking (SELECT ... FOR UPDATE)
                $buku = Buku::where('id', $validated['buku_id'])->lockForUpdate()->first();

                if (!$buku) {
                    throw new \DomainException('book-not-found:Buku yang dipilih tidak ditemukan.');
                }

                // 2. Integritas Bisnis: Cek stok buku > 0
                if ($buku->stok < 1) {
                    throw new \DomainException("book-out-of-stock:Stok buku '{$buku->judul}' telah habis (0). Peminjaman tidak dapat diproses.");
                }

                $nim = strtoupper(trim($validated['nim_peminjam']));

                // 3. Integritas Bisnis: Cegah peminjaman ganda buku yang sama oleh pemustaka yang sama
                $sedangPinjam = Peminjaman::where('buku_id', $buku->id)
                    ->where('nim_peminjam', $nim)
                    ->where('status', 'dipinjam')
                    ->lockForUpdate()
                    ->exists();

                if ($sedangPinjam) {
                    throw new \DomainException("duplicate-active-loan:Mahasiswa dengan NIM {$nim} masih memiliki peminjaman aktif untuk buku '{$buku->judul}'. Selesaikan peminjaman sebelumnya terlebih dahulu.");
                }

                // 4. Kurangi stok buku secara atomik
                $buku->decrement('stok');

                // 5. Generate kode pinjam unik
                $kodePinjam = 'PJ-' . date('Ymd') . '-' . strtoupper(Str::random(5));
                while (Peminjaman::where('kode_pinjam', $kodePinjam)->exists()) {
                    $kodePinjam = 'PJ-' . date('Ymd') . '-' . strtoupper(Str::random(5));
                }

                // 6. Simpan transaksi peminjaman
                $pinjam = Peminjaman::create([
                    'kode_pinjam' => $kodePinjam,
                    'buku_id' => $buku->id,
                    'nim_peminjam' => $nim,
                    'nama_peminjam' => trim($validated['nama_peminjam']),
                    'tanggal_pinjam' => $tglPinjam,
                    'tanggal_jatuh_tempo' => $tglJatuhTempo,
                    'tanggal_kembali' => null,
                    'status' => 'dipinjam',
                ]);

                $pinjam->load('buku');
                return $pinjam;
            }, 3);

            return $this->createdResponse(
                data: new PeminjamanResource($peminjaman),
                locationUrl: url("/api/v1/peminjamans/{$peminjaman->id}"),
                message: 'Transaksi peminjaman buku berhasil dicatat secara atomik (ACID).'
            );
        } catch (\DomainException $e) {
            [$slug, $msg] = explode(':', $e->getMessage(), 2);
            $status = match ($slug) {
                'book-out-of-stock' => 422,
                'duplicate-active-loan' => 409,
                'book-not-found' => 404,
                default => 400,
            };
            $title = match ($slug) {
                'book-out-of-stock' => 'Unprocessable Entity — Book Out of Stock',
                'duplicate-active-loan' => 'Conflict — Active Loan Exists',
                'book-not-found' => 'Resource Not Found',
                default => 'Bad Request',
            };

            return $this->problemResponse(
                title: $title,
                detail: $msg,
                status: $status,
                errorSlug: $slug
            );
        }
    }

    /**
     * PATCH /api/v1/peminjamans/{id}/kembalikan (Wajib Bearer Token)
     * Mengembalikan buku peminjaman secara atomik dan memulihkan stok inventaris (+1).
     */
    public function kembalikan(Request $request, string $id): JsonResponse
    {
        try {
            $peminjaman = DB::transaction(function () use ($id) {
                // 1. Kunci baris peminjaman
                $pinjam = Peminjaman::where('id', $id)
                    ->orWhere('kode_pinjam', $id)
                    ->lockForUpdate()
                    ->first();

                if (!$pinjam) {
                    throw new \DomainException("loan-not-found:Transaksi peminjaman dengan ID atau kode '{$id}' tidak ditemukan.");
                }

                // 2. Integritas Bisnis: Tolak jika buku sudah dikembalikan sebelumnya (Idempotency / Conflict check)
                if ($pinjam->status === 'dikembalikan') {
                    $tgl = $pinjam->tanggal_kembali ? $pinjam->tanggal_kembali->format('Y-m-d') : 'sebelumnya';
                    throw new \DomainException("loan-already-returned:Buku pada transaksi '{$pinjam->kode_pinjam}' sudah pernah dikembalikan pada tanggal {$tgl}.");
                }

                // 3. Kunci baris buku terkait
                $buku = Buku::where('id', $pinjam->buku_id)->lockForUpdate()->firstOrFail();

                // 4. Ubah status transaksi dan tanggal kembali
                $pinjam->update([
                    'status' => 'dikembalikan',
                    'tanggal_kembali' => now()->toDateString(),
                ]);

                // 5. Pulihkan stok buku secara atomik
                $buku->increment('stok');

                $pinjam->load('buku');
                return $pinjam;
            }, 3);

            return $this->successResponse(
                data: new PeminjamanResource($peminjaman),
                message: "Buku '{$peminjaman->buku->judul}' pada transaksi {$peminjaman->kode_pinjam} berhasil dikembalikan dan stok telah dipulihkan secara atomik."
            );
        } catch (\DomainException $e) {
            [$slug, $msg] = explode(':', $e->getMessage(), 2);
            $status = match ($slug) {
                'loan-already-returned' => 409,
                'loan-not-found' => 404,
                default => 400,
            };
            $title = match ($slug) {
                'loan-already-returned' => 'Conflict — Book Already Returned',
                'loan-not-found' => 'Resource Not Found',
                default => 'Bad Request',
            };

            return $this->problemResponse(
                title: $title,
                detail: $msg,
                status: $status,
                errorSlug: $slug
            );
        }
    }
}
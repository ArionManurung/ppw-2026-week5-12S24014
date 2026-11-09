<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BukuResource;
use App\Models\Buku;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class BukuApiController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/v1/bukus
     * Mengambil koleksi buku terpaginasi lengkap dengan filter, pencarian, pengurutan, & Eager Loading (Bebas N+1).
     */
    public function index(Request $request): JsonResponse
    {
        // 1. Sanitasi parameter pengurutan (mencegah SQL Injection pada ORDER BY)
        $allowedSorts = ['judul', 'penulis', 'tahun_terbit', 'stok', 'created_at'];
        $sortBy = in_array($request->query('sort'), $allowedSorts) ? $request->query('sort') : 'created_at';
        $sortDir = strtolower($request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $perPage = min(max((int) $request->query('per_page', 10), 1), 50);

        // 2. Kueri Eloquent teroptimasi dengan Eager Loading kategori dan agregasi withCount peminjaman
        $paginator = Buku::query()
            ->with('kategori')
            ->withCount('peminjamans')
            ->when($request->query('search'), function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('judul', 'like', "%{$search}%")
                        ->orWhere('penulis', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%")
                        ->orWhere('penerbit', 'like', "%{$search}%");
                });
            })
            ->when($request->query('kategori_id'), function ($q, $kategoriId) {
                $q->where('kategori_id', $kategoriId);
            })
            ->when($request->filled('tersedia'), function ($q) use ($request) {
                if ($request->boolean('tersedia')) {
                    $q->where('stok', '>', 0);
                } else {
                    $q->where('stok', '<=', 0);
                }
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        // 3. Kembalikan koleksi terbungkus BukuResource beserta metadata paginasi
        return $this->successResponse(
            data: BukuResource::collection($paginator->items()),
            message: 'Koleksi katalog buku perpustakaan berhasil diambil.',
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
     * GET /api/v1/bukus/{id}
     * Mengambil detail satu buku berdasarkan ID atau ISBN beserta objek kategori dan riwayat peminjaman.
     */
    public function show(string $identifier): JsonResponse
    {
        $buku = Buku::with('kategori')
            ->withCount('peminjamans')
            ->with('peminjamans')
            ->where('id', $identifier)
            ->orWhere('isbn', $identifier)
            ->first();

        if (!$buku) {
            return $this->problemResponse(
                title: 'Resource Not Found',
                detail: "Buku dengan ID atau ISBN '{$identifier}' tidak ditemukan di katalog perpustakaan.",
                status: 404,
                errorSlug: 'book-not-found'
            );
        }

        return $this->successResponse(
            data: new BukuResource($buku),
            message: 'Detail buku berhasil ditemukan.'
        );
    }

    /**
     * POST /api/v1/bukus (Wajib Bearer Token)
     * Menambahkan judul buku baru ke inventaris -> Mengembalikan 201 Created + Header Location.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'isbn' => 'required|string|max:20|unique:bukus,isbn',
            'judul' => 'required|string|min:3|max:150',
            'penulis' => 'required|string|max:100',
            'penerbit' => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|between:1900,2099',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok' => 'required|integer|min:0|max:1000',
            'sinopsis' => 'nullable|string',
        ], [
            'isbn.required' => 'Nomor ISBN wajib diisi.',
            'isbn.unique' => 'Nomor ISBN sudah terdaftar di sistem.',
            'judul.required' => 'Judul buku wajib diisi.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid di database.',
            'stok.min' => 'Stok buku tidak boleh bernilai negatif.',
        ]);

        $buku = Buku::create($validated);
        $buku->load('kategori');
        $buku->loadCount('peminjamans');

        return $this->createdResponse(
            data: new BukuResource($buku),
            locationUrl: url("/api/v1/bukus/{$buku->id}"),
            message: 'Buku baru berhasil ditambahkan ke katalog perpustakaan.'
        );
    }

    /**
     * PUT / PATCH /api/v1/bukus/{id} (Wajib Bearer Token)
     * Mendemonstrasikan perbedaan semantik PUT (Full Replacement) vs PATCH (Partial Update).
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return $this->problemResponse(
                title: 'Resource Not Found',
                detail: "Buku dengan ID '{$id}' tidak ditemukan untuk diperbarui.",
                status: 404,
                errorSlug: 'book-not-found'
            );
        }

        $isPut = $request->isMethod('put');

        if ($isPut) {
            // SEMANTIK PUT: Seluruh atribut representasi WAJIB dikirimkan secara utuh!
            $validated = $request->validate([
                'isbn' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('bukus', 'isbn')->ignore($buku->id),
                ],
                'judul' => 'required|string|min:3|max:150',
                'penulis' => 'required|string|max:100',
                'penerbit' => 'required|string|max:100',
                'tahun_terbit' => 'required|integer|between:1900,2099',
                'kategori_id' => 'required|exists:kategoris,id',
                'stok' => 'required|integer|min:0|max:1000',
                'sinopsis' => 'nullable|string',
            ]);

            // Pada PUT murni, jika field nullable tidak dikirim di body, nilainya direset ke null
            $validated['sinopsis'] = $request->input('sinopsis', null);
        } else {
            // SEMANTIK PATCH: HANYA memvalidasi & mengubah atribut yang disertakan di body saja!
            $validated = $request->validate([
                'isbn' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('bukus', 'isbn')->ignore($buku->id),
                ],
                'judul' => 'sometimes|required|string|min:3|max:150',
                'penulis' => 'sometimes|required|string|max:100',
                'penerbit' => 'sometimes|required|string|max:100',
                'tahun_terbit' => 'sometimes|required|integer|between:1900,2099',
                'kategori_id' => 'sometimes|required|exists:kategoris,id',
                'stok' => 'sometimes|required|integer|min:0|max:1000',
                'sinopsis' => 'sometimes|nullable|string',
            ]);

            if (empty($validated)) {
                return $this->problemResponse(
                    title: 'Bad Request',
                    detail: 'Permintaan PATCH wajib menyertakan minimal satu atribut yang ingin diperbarui.',
                    status: 400,
                    errorSlug: 'empty-patch-payload'
                );
            }
        }

        $buku->update($validated);
        $buku->load('kategori');
        $buku->loadCount('peminjamans');

        return $this->successResponse(
            data: new BukuResource($buku),
            message: $isPut
                ? 'Seluruh data representasi buku berhasil diganti (PUT).'
                : 'Atribut data buku berhasil diperbarui sebagian (PATCH).'
        );
    }

    /**
     * DELETE /api/v1/bukus/{id} (Wajib Bearer Token)
     * Menghapus buku -> Ditolak dengan 409 Conflict jika buku memiliki peminjaman aktif.
     */
    public function destroy(string $id): JsonResponse
    {
        $buku = Buku::withCount('peminjamans')->find($id);

        if (!$buku) {
            return $this->problemResponse(
                title: 'Resource Not Found',
                detail: "Buku dengan ID '{$id}' tidak ditemukan.",
                status: 404,
                errorSlug: 'book-not-found'
            );
        }

        // Aturan Bisnis Integritas: Tolak penghapusan jika ada mahasiswa yang sedang meminjam buku ini!
        $activeLoansCount = $buku->peminjamans()->where('status', 'dipinjam')->count();

        if ($activeLoansCount > 0) {
            return $this->problemResponse(
                title: 'Conflict — Book Has Active Loans',
                detail: "Buku '{$buku->judul}' tidak dapat dihapus karena saat ini sedang dipinjam oleh {$activeLoansCount} pemustaka aktif.",
                status: 409,
                errorSlug: 'book-has-active-loans'
            );
        }

        $buku->delete();

        return $this->noContentResponse();
    }

    /**
     * GET /api/v1/bukus/{id}/referensi-eksternal
     * Layanan REST Consumer: Memanggil OpenLibrary REST API publik untuk pengayaan metadata buku.
     */
    public function referensiEksternal(string $id): JsonResponse
    {
        $buku = Buku::where('id', $id)->orWhere('isbn', $id)->first();

        if (!$buku) {
            return $this->problemResponse(
                title: 'Resource Not Found',
                detail: "Buku dengan pengenal '{$id}' tidak ditemukan di database lokal.",
                status: 404,
                errorSlug: 'book-not-found'
            );
        }

        // Bersihkan ISBN dari strip / spasi untuk kueri ke API publik
        $cleanIsbn = preg_replace('/[^0-9X]/i', '', $buku->isbn);

        try {
            // Panggil OpenLibrary REST API dengan batas waktu (timeout) 8 detik
            $response = Http::timeout(8)
                ->acceptJson()
                ->get("https://openlibrary.org/api/books", [
                    'bibkeys' => "ISBN:{$cleanIsbn}",
                    'format' => 'json',
                    'jscmd' => 'data',
                ]);

            $bibKey = "ISBN:{$cleanIsbn}";
            $openLibData = $response->json()[$bibKey] ?? null;

            if (!$openLibData) {
                // Fallback informasi jika buku tidak terdaftar di indeks OpenLibrary global
                return $this->successResponse(
                    data: [
                        'local_book' => [
                            'id' => $buku->id,
                            'isbn' => $buku->isbn,
                            'judul' => $buku->judul,
                            'penulis' => $buku->penulis,
                        ],
                        'external_found' => false,
                        'source' => 'OpenLibrary REST API (https://openlibrary.org)',
                        'note' => 'Metadata buku tidak ditemukan di basis data OpenLibrary internasional.',
                    ],
                    message: 'Data buku lokal ditemukan, namun metadata eksternal tidak tersedia di OpenLibrary.'
                );
            }

            return $this->successResponse(
                data: [
                    'local_book' => [
                        'id' => $buku->id,
                        'isbn' => $buku->isbn,
                        'judul' => $buku->judul,
                        'penulis' => $buku->penulis,
                    ],
                    'external_found' => true,
                    'source' => 'OpenLibrary REST API',
                    'title' => $openLibData['title'] ?? $buku->judul,
                    'authors' => collect($openLibData['authors'] ?? [])->pluck('name')->toArray(),
                    'publishers' => collect($openLibData['publishers'] ?? [])->pluck('name')->toArray(),
                    'publish_date' => $openLibData['publish_date'] ?? null,
                    'number_of_pages' => $openLibData['number_of_pages'] ?? null,
                    'cover_url' => $openLibData['cover']['medium'] ?? ($openLibData['cover']['small'] ?? null),
                    'openlibrary_url' => $openLibData['url'] ?? null,
                ],
                message: 'Data referensi metadata eksternal berhasil diambil dari OpenLibrary API.'
            );
        } catch (\Exception $e) {
            // Graceful handling jika koneksi internet timeout atau terputus
            return $this->successResponse(
                data: [
                    'local_book' => [
                        'id' => $buku->id,
                        'isbn' => $buku->isbn,
                        'judul' => $buku->judul,
                    ],
                    'external_found' => false,
                    'error' => 'Koneksi ke OpenLibrary API mengalami kendala atau batas waktu habis (timeout).',
                ],
                message: 'Gagal menghubungi penyedia layanan eksternal OpenLibrary secara langsung.'
            );
        }
    }
}

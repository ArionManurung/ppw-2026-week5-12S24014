<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PeminjamanController extends Controller
{
    /**
     * Tampilkan riwayat dan sirkulasi peminjaman buku.
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        if ($request->filled('search')) {
            session(['last_peminjaman_search' => $search]);
        }

        $peminjamans = Peminjaman::with('buku.kategori')
            ->when($status, function ($query, $st) {
                return $query->where('status', $st);
            })
            ->when($search, function ($query, $keyword) {
                return $query->where(function ($q) use ($keyword) {
                    $q->where('kode_pinjam', 'like', "%{$keyword}%")
                        ->orWhere('nim_peminjam', 'like', "%{$keyword}%")
                        ->orWhere('nama_peminjam', 'like', "%{$keyword}%")
                        ->orWhereHas('buku', function ($bq) use ($keyword) {
                            $bq->where('judul', 'like', "%{$keyword}%")
                                ->orWhere('isbn', 'like', "%{$keyword}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('peminjaman.index', compact('peminjamans', 'status', 'search'));
    }

    /**
     * Tampilkan formulir peminjaman buku baru.
     */
    public function create(Request $request)
    {
        $selectedBukuId = $request->query('buku_id');
        $bukus = Buku::orderBy('judul')->get();

        return view('peminjaman.create', compact('bukus', 'selectedBukuId'));
    }

    /**
     * Simpan transaksi peminjaman buku baru berbasis ACID & Pessimistic Locking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'nim_peminjam' => 'required|string|min:6|max:20',
            'nama_peminjam' => 'required|string|min:3|max:150',
            'tanggal_pinjam' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjam',
        ], [
            'buku_id.required' => 'Pilih buku yang hendak dipinjam.',
            'buku_id.exists' => 'Buku yang dipilih tidak ditemukan.',
            'nim_peminjam.required' => 'NIM peminjam wajib diisi.',
            'nama_peminjam.required' => 'Nama lengkap peminjam wajib diisi.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib ditentukan.',
            'tanggal_jatuh_tempo.required' => 'Tanggal jatuh tempo wajib ditentukan.',
            'tanggal_jatuh_tempo.after_or_equal' => 'Tanggal jatuh tempo tidak boleh sebelum tanggal pinjam.',
        ]);

        try {
            $peminjaman = DB::transaction(function () use ($validated) {
                // 1. Kunci baris buku menggunakan pessimistic locking (SELECT ... FOR UPDATE)
                $buku = Buku::where('id', $validated['buku_id'])->lockForUpdate()->firstOrFail();

                // 2. Validasi stok > 0 secara atomik
                if ($buku->stok < 1) {
                    throw new \RuntimeException("Stok buku '{$buku->judul}' telah habis (0). Peminjaman tidak dapat diproses!");
                }

                $nim = strtoupper(trim($validated['nim_peminjam']));

                // 3. Cegah NIM yang sama meminjam buku yang sama yang belum dikembalikan
                $sedangPinjam = Peminjaman::where('buku_id', $buku->id)
                    ->where('nim_peminjam', $nim)
                    ->where('status', 'dipinjam')
                    ->lockForUpdate()
                    ->exists();

                if ($sedangPinjam) {
                    throw new \RuntimeException("Mahasiswa dengan NIM {$nim} masih memiliki pinjaman aktif untuk buku '{$buku->judul}'. Pengembalian harus diselesaikan terlebih dahulu!");
                }

                // 4. Kurangi stok buku secara atomik
                $buku->decrement('stok');

                // 5. Generate kode pinjam unik
                $kodePinjam = 'PJ-' . date('Ymd') . '-' . strtoupper(Str::random(5));
                while (Peminjaman::where('kode_pinjam', $kodePinjam)->exists()) {
                    $kodePinjam = 'PJ-' . date('Ymd') . '-' . strtoupper(Str::random(5));
                }

                // 6. Catat transaksi ke tabel peminjamans
                return Peminjaman::create([
                    'kode_pinjam' => $kodePinjam,
                    'buku_id' => $buku->id,
                    'nim_peminjam' => $nim,
                    'nama_peminjam' => trim($validated['nama_peminjam']),
                    'tanggal_pinjam' => $validated['tanggal_pinjam'],
                    'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'],
                    'tanggal_kembali' => null,
                    'status' => 'dipinjam',
                ]);
            }, 3);

            // Simpan riwayat peminjam terakhir ke Session
            session([
                'last_borrower_nim' => $peminjaman->nim_peminjam,
                'last_borrower_name' => $peminjaman->nama_peminjam,
            ]);

            return redirect()->route('peminjaman.index')
                ->with('success', "Peminjaman buku berhasil dicatat dengan Kode Pinjam {$peminjaman->kode_pinjam} secara atomik!");
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (QueryException $e) {
            Log::error('[DB EXCEPTION PEMINJAMAN]: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan integritas basis data saat memproses peminjaman.');
        }
    }

    /**
     * Tampilkan detail transaksi peminjaman.
     */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load('buku.kategori');
        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Proses pengembalian buku berbasis ACID & Concurrency Locking.
     */
    public function kembali(int $id)
    {
        try {
            $peminjaman = DB::transaction(function () use ($id) {
                // 1. Kunci baris peminjaman
                $pinjam = Peminjaman::where('id', $id)->lockForUpdate()->firstOrFail();

                if ($pinjam->status === 'dikembalikan') {
                    throw new \RuntimeException("Buku dengan Kode Pinjam {$pinjam->kode_pinjam} sudah pernah dikembalikan!");
                }

                // 2. Kunci baris buku terkait
                $buku = Buku::where('id', $pinjam->buku_id)->lockForUpdate()->firstOrFail();

                // 3. Ubah status peminjaman menjadi 'dikembalikan' dan isi tanggal_kembali
                $pinjam->update([
                    'status' => 'dikembalikan',
                    'tanggal_kembali' => now()->toDateString(),
                ]);

                // 4. Pulihkan stok buku secara atomik
                $buku->increment('stok');

                return $pinjam;
            }, 3);

            return back()->with('success', "Buku pada transaksi {$peminjaman->kode_pinjam} berhasil dikembalikan dan stok telah dipulihkan secara atomik!");
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (QueryException $e) {
            Log::error('[DB EXCEPTION PENGEMBALIAN]: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan integritas basis data saat memproses pengembalian buku.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BukuController extends Controller
{
    /**
     * Tampilkan katalog buku dengan relasi kategori, agregasi withCount, pencarian dinamis, dan analitik DB::table.
     */
    public function index(Request $request)
    {
        // Simpan riwayat pencarian terakhir ke Sesi
        if ($request->filled('search')) {
            session(['last_buku_search' => $request->search]);
        }

        // Eloquent Eager Loading relasi kategori + withCount peminjaman + Filter dinamis when()
        $bukus = Buku::with('kategori')
            ->withCount('peminjamans')
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                        ->orWhere('penulis', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%")
                        ->orWhere('penerbit', 'like', "%{$search}%");
                });
            })
            ->when($request->kategori_id, function ($query, $kategoriId) {
                return $query->where('kategori_id', $kategoriId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Rekapitulasi Statistik Perpustakaan menggunakan Fluent Query Builder (DB::table)
        $stats = [
            'total_judul' => DB::table('bukus')->count(),
            'total_stok' => (int) DB::table('bukus')->sum('stok'),
            'total_dipinjam' => DB::table('peminjamans')->where('status', 'dipinjam')->count(),
            'total_dikembalikan' => DB::table('peminjamans')->where('status', 'dikembalikan')->count(),
        ];

        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('buku.index', compact('bukus', 'stats', 'kategoris'));
    }

    /**
     * Tampilkan formulir pendaftaran buku baru.
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('buku.create', compact('kategoris'));
    }

    /**
     * Simpan data buku baru ke database dengan validasi ketat sisi server.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'isbn' => 'required|string|max:20|unique:bukus,isbn',
            'judul' => 'required|string|min:5|max:150',
            'penulis' => 'required|string|max:100',
            'penerbit' => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|between:1900,2099',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'sinopsis' => 'nullable|string',
        ], [
            'isbn.required' => 'ISBN wajib diisi.',
            'isbn.unique' => 'Nomor ISBN sudah terdaftar.',
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.min' => 'Judul buku minimal terdiri dari 5 karakter.',
            'stok.min' => 'Stok buku tidak boleh kurang dari 0.',
            'kategori_id.required' => 'Pilih kategori buku.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
        ]);

        Buku::create($validated);

        return redirect()->route('buku.index')->with('success', 'Buku baru berhasil ditambahkan ke katalog!');
    }

    /**
     * Tampilkan rincian buku tertentu (Eager Loading relasi kategori).
     */
    public function show(Buku $buku)
    {
        $buku->load('kategori');
        return view('buku.show', compact('buku'));
    }

    /**
     * Tampilkan formulir edit data buku.
     */
    public function edit(Buku $buku)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    /**
     * Perbarui data buku di database.
     */
    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'isbn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('bukus', 'isbn')->ignore($buku->id),
            ],
            'judul' => 'required|string|min:5|max:150',
            'penulis' => 'required|string|max:100',
            'penerbit' => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|between:1900,2099',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'sinopsis' => 'nullable|string',
        ]);

        $buku->update($validated);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    /**
     * Hapus buku dari katalog.
     */
    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus dari katalog!');
    }
}

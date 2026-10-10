<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    /**
     * Tampilkan daftar seluruh kategori buku (Paginated).
     */
    public function index()
    {
        $kategoris = Kategori::withCount('bukus')->latest()->paginate(10);
        return view('kategori.index', compact('kategoris'));
    }

    /**
     * Tampilkan formulir tambah kategori baru.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Simpan kategori baru ke database dengan validasi ketat sisi server.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_kategori' => 'required|string|max:10|unique:kategoris,kode_kategori',
            'nama_kategori' => 'required|string|max:100',
        ], [
            'kode_kategori.required' => 'Kode kategori wajib diisi.',
            'kode_kategori.unique' => 'Kode kategori sudah digunakan.',
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        Kategori::create($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori buku berhasil ditambahkan!');
    }

    /**
     * Tampilkan rincian kategori beserta daftar buku di dalamnya.
     */
    public function show(Kategori $kategori)
    {
        $kategori->load('bukus');
        return view('kategori.show', compact('kategori'));
    }

    /**
     * Tampilkan formulir edit data kategori.
     */
    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Perbarui data kategori di database.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'kode_kategori' => [
                'required',
                'string',
                'max:10',
                Rule::unique('kategoris', 'kode_kategori')->ignore($kategori->id),
            ],
            'nama_kategori' => 'required|string|max:100',
        ]);

        $kategori->update($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori buku berhasil diperbarui!');
    }

    /**
     * Hapus kategori dari database.
     */
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori buku berhasil dihapus!');
    }
}

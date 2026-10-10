<?php

namespace App\Http\Controllers;

use App\Models\Pengunjung;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengunjungController extends Controller
{
    /**
     * Tampilkan daftar seluruh riwayat log kunjungan perpustakaan (Paginated).
     */
    public function index(Request $request)
    {
        $query = Pengunjung::query()->latest();

        // Fitur pencarian nama / NIM / instansi
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim_nik', 'like', "%{$search}%")
                  ->orWhere('institusi_prodi', 'like', "%{$search}%");
            });
        }

        // Fitur filter kategori jenis pengunjung
        if ($request->filled('jenis') && in_array($request->jenis, ['Mahasiswa', 'Dosen / Staf', 'Tamu / Umum'])) {
            $query->where('jenis_pengunjung', $request->jenis);
        }

        $pengunjungs = $query->paginate(10)->withQueryString();

        // Statistik ringkas pengunjung
        $totalSemua = Pengunjung::count();
        $totalHariIni = Pengunjung::whereDate('created_at', Carbon::today())->count();
        $totalMahasiswa = Pengunjung::where('jenis_pengunjung', 'Mahasiswa')->count();

        return view('pengunjung.index', compact('pengunjungs', 'totalSemua', 'totalHariIni', 'totalMahasiswa'));
    }

    /**
     * Tampilkan formulir buku tamu pendaftaran kunjungan perpustakaan.
     */
    public function create()
    {
        return view('pengunjung.create');
    }

    /**
     * Simpan data kunjungan baru ke database dengan validasi sisi server.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim_nik' => 'required|string|max:30',
            'nama' => 'required|string|min:3|max:150',
            'jenis_pengunjung' => 'required|in:Mahasiswa,Dosen / Staf,Tamu / Umum',
            'institusi_prodi' => 'nullable|string|max:100',
            'keperluan' => 'required|string|max:200',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'nim_nik.required' => 'Nomor Identitas (NIM/NIK/No. KTP) wajib diisi.',
            'nama.required' => 'Nama lengkap pengunjung wajib diisi.',
            'nama.min' => 'Nama pengunjung minimal terdiri dari 3 karakter.',
            'jenis_pengunjung.required' => 'Pilih kategori jenis pengunjung.',
            'keperluan.required' => 'Pilih atau sebutkan keperluan kunjungan Anda.',
        ]);

        Pengunjung::create($validated);

        return redirect()->route('pengunjung.index')->with('success', 'Selamat datang di Perpustakaan Del! Log kehadiran Anda berhasil dicatat.');
    }

    /**
     * Tampilkan rincian riwayat kunjungan.
     */
    public function show(Pengunjung $pengunjung)
    {
        return view('pengunjung.show', compact('pengunjung'));
    }

    /**
     * Hapus data log pengunjung dari sistem.
     */
    public function destroy(Pengunjung $pengunjung)
    {
        $pengunjung->delete();

        return redirect()->route('pengunjung.index')->with('success', 'Catatan kunjungan berhasil dihapus.');
    }
}

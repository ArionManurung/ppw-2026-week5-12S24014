<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengunjung;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Pustakawan Uji (Sesuai Spesifikasi Modul Praktikum Minggu 6)
        $pustakawan = User::updateOrCreate(
            ['email' => 'pustakawan@del.ac.id'],
            [
                'name' => 'Pustakawan Del',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Kategori Buku Baku
        $kategorisData = [
            ['kode_kategori' => 'KAT-INF', 'nama_kategori' => 'Informatika & Komputer'],
            ['kode_kategori' => 'KAT-SIS', 'nama_kategori' => 'Sistem Informasi & Bisnis'],
            ['kode_kategori' => 'KAT-TEK', 'nama_kategori' => 'Teknik Elektro & Robotika'],
            ['kode_kategori' => 'KAT-MAT', 'nama_kategori' => 'Matematika & Sains Data'],
            ['kode_kategori' => 'KAT-UMM', 'nama_kategori' => 'Literasi Umum & Softskills'],
        ];

        $createdKategoris = [];
        foreach ($kategorisData as $kat) {
            $createdKategoris[] = Kategori::updateOrCreate(
                ['kode_kategori' => $kat['kode_kategori']],
                ['nama_kategori' => $kat['nama_kategori']]
            );
        }

        // 3. Generate koleksi buku dasar jika belum ada
        if (Buku::count() < 10) {
            foreach ($createdKategoris as $kategori) {
                Buku::factory(4)->create([
                    'kategori_id' => $kategori->id,
                ]);
            }
        }

        // 4. Buku Khusus Pengujian Transaksi ACID (Stok = 0 dan Stok = 1)
        // Sampel Stok = 0: Digunakan untuk uji pembuktian Rollback & Penolakan ACID
        $bukuStokNol = Buku::updateOrCreate(
            ['isbn' => '978-602-0000-00-1'],
            [
                'judul' => 'Sistem Terdistribusi & Algoritma Konsensus ACID',
                'penulis' => 'Chandro Pardede, S.Kom., M.Sc.',
                'penerbit' => 'Del Press',
                'tahun_terbit' => 2026,
                'kategori_id' => $createdKategoris[0]->id,
                'stok' => 0,
                'sinopsis' => 'Buku pegangan transaksi atomik, isolasi basis data, dan penanganan konkurensi locking di lingkungan multi-user.',
            ]
        );

        // Sampel Stok = 1: Digunakan untuk uji peminjaman batas kritis
        $bukuStokSatu = Buku::updateOrCreate(
            ['isbn' => '978-602-0000-01-8'],
            [
                'judul' => 'Pemrograman Web Lanjut & Laravel 11 Concurrency',
                'penulis' => 'Tim Pengajar PPW',
                'penerbit' => 'Del Press',
                'tahun_terbit' => 2026,
                'kategori_id' => $createdKategoris[0]->id,
                'stok' => 1,
                'sinopsis' => 'Panduan komprehensif implementasi PDO, Fluent Query Builder, dan Pessimistic Locking pada Laravel 11.',
            ]
        );

        // 5. Sampel Transaksi Peminjaman (Aktif & Dikembalikan)
        // Transaksi 1: Status 'dipinjam' (Aktif)
        Peminjaman::updateOrCreate(
            ['kode_pinjam' => 'PJ-20261014-001'],
            [
                'buku_id' => $bukuStokSatu->id,
                'nim_peminjam' => '12S24014',
                'nama_peminjam' => 'Arion Manurung',
                'tanggal_pinjam' => now()->subDays(3)->toDateString(),
                'tanggal_jatuh_tempo' => now()->addDays(4)->toDateString(),
                'tanggal_kembali' => null,
                'status' => 'dipinjam',
            ]
        );

        // Transaksi 2: Status 'dikembalikan' (Selesai)
        $bukuSampel2 = Buku::where('id', '!=', $bukuStokNol->id)->where('id', '!=', $bukuStokSatu->id)->first() ?? $bukuStokSatu;
        Peminjaman::updateOrCreate(
            ['kode_pinjam' => 'PJ-20261014-002'],
            [
                'buku_id' => $bukuSampel2->id,
                'nim_peminjam' => '12S24001',
                'nama_peminjam' => 'Budi Siregar',
                'tanggal_pinjam' => now()->subDays(10)->toDateString(),
                'tanggal_jatuh_tempo' => now()->subDays(3)->toDateString(),
                'tanggal_kembali' => now()->subDays(2)->toDateString(),
                'status' => 'dikembalikan',
            ]
        );

        // 6. Mock Data Pengunjung Perpustakaan (Jika masih kosong)
        if (Pengunjung::count() < 5) {
            Pengunjung::factory(10)->create();
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Buku;
use App\Models\Pengunjung;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $kategoris = [
            ['kode_kategori' => 'KAT-INF', 'nama_kategori' => 'Informatika & Komputer'],
            ['kode_kategori' => 'KAT-SIS', 'nama_kategori' => 'Sistem Informasi & Bisnis'],
            ['kode_kategori' => 'KAT-TEK', 'nama_kategori' => 'Teknik Elektro & Robotika'],
            ['kode_kategori' => 'KAT-MAT', 'nama_kategori' => 'Matematika & Sains Data'],
            ['kode_kategori' => 'KAT-UMM', 'nama_kategori' => 'Literasi Umum & Softskills'],
        ];

        foreach ($kategoris as $kat) {
            $createdKategori = Kategori::create($kat);

            // Buat 4-5 buku untuk setiap kategori (Total 20+ buku)
            Buku::factory(4)->create([
                'kategori_id' => $createdKategori->id,
            ]);
        }

        // Buat 15 mock data pengunjung perpustakaan
        Pengunjung::factory(15)->create();
    }
}

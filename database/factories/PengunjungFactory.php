<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengunjung>
 */
class PengunjungFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenis = $this->faker->randomElement(['Mahasiswa', 'Dosen / Staf', 'Tamu / Umum']);
        $keperluan = $this->faker->randomElement([
            'Membaca Buku & Jurnal',
            'Peminjaman / Pengembalian Buku',
            'Diskusi & Belajar Kelompok',
            'Mencari Referensi Tugas Akhir',
            'Menggunakan Fasilitas Komputer',
        ]);
        $prodis = [
            'S1 Informatika',
            'S1 Sistem Informasi',
            'S1 Teknik Elektro',
            'S1 Manajemen Rekayasa',
            'S1 Bioteknologi',
            'D3 Teknologi Informasi',
            'D3 Teknologi Komputer',
            'D4 Teknologi Rekayasa Perangkat Lunak',
        ];

        return [
            'nim_nik' => $jenis === 'Mahasiswa' ? $this->faker->numerify('12S#####') : ($jenis === 'Dosen / Staf' ? $this->faker->numerify('01######') : '-'),
            'nama' => $this->faker->name(),
            'jenis_pengunjung' => $jenis,
            'institusi_prodi' => $jenis === 'Mahasiswa' ? $this->faker->randomElement($prodis) : ($jenis === 'Dosen / Staf' ? 'Fakultas Informatika & Elektro' : $this->faker->company()),
            'keperluan' => $keperluan,
            'keterangan' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}

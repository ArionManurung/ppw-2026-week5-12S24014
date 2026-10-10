<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengunjungs', function (Blueprint $table) {
            $table->id();
            $table->string('nim_nik', 30);
            $table->string('nama', 150);
            $table->string('jenis_pengunjung', 50); // Mahasiswa, Dosen / Staf, Tamu / Umum
            $table->string('institusi_prodi', 100)->nullable(); // Program Studi atau Asal Institusi
            $table->string('keperluan', 200); // Membaca, Peminjaman, Belajar Kelompok, dll
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengunjungs');
    }
};

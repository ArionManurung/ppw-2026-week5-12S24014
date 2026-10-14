<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PengunjungController;
use App\Http\Controllers\PeminjamanController;

// Rute Publik (Read-Only)
Route::get('/', fn() => redirect()->route('buku.index'));
Route::get('buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::resource('pengunjung', PengunjungController::class)->except(['destroy']);

// Autentikasi Pustakawan (Login & Logout)
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Rute Terproteksi Khusus Pustakawan (Mutasi Data, Transaksi ACID & Sirkulasi)
Route::middleware('auth')->group(function () {
    // Mutasi Buku
    Route::get('buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('buku', [BukuController::class, 'store'])->name('buku.store');
    Route::get('buku/{buku}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('buku/{buku}', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');

    // Mutasi Kategori
    Route::get('kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // Transaksi ACID Sirkulasi Peminjaman & Pengembalian
    Route::get('peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::post('peminjaman/{id}/kembali', [PeminjamanController::class, 'kembali'])->name('peminjaman.kembali');

    // Penghapusan Log Pengunjung
    Route::delete('pengunjung/{pengunjung}', [PengunjungController::class, 'destroy'])->name('pengunjung.destroy');
});

// Detail Buku & Kategori (Publik Read-Only setelah rute create)
Route::get('buku/{buku}', [BukuController::class, 'show'])->name('buku.show');
Route::get('kategori/{kategori}', [KategoriController::class, 'show'])->name('kategori.show');

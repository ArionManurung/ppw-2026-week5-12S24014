<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PengunjungController;
use App\Http\Controllers\PeminjamanController;

Route::get('/', fn() => redirect()->route('buku.index'));

// Modul Katalog Buku & Kategori
Route::resource('buku', BukuController::class);
Route::resource('kategori', KategoriController::class);
Route::resource('pengunjung', PengunjungController::class);

// Modul Transaksi Sirkulasi Peminjaman & Pengembalian (ACID)
Route::get('peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
Route::get('peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
Route::post('peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
Route::get('peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
Route::post('peminjaman/{id}/kembali', [PeminjamanController::class, 'kembali'])->name('peminjaman.kembali');

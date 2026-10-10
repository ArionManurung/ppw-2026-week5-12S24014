<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengunjung extends Model
{
    /** @use HasFactory<\Database\Factories\PengunjungFactory> */
    use HasFactory;

    protected $fillable = [
        'nim_nik',
        'nama',
        'jenis_pengunjung',
        'institusi_prodi',
        'keperluan',
        'keterangan',
    ];
}

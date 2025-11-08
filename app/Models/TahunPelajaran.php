<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunPelajaran extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'nama_tahun_pelajaran',
        'status',
    ];

    /**
     * Tipe data asli untuk atribut.
     * Ini akan otomatis mengubah '1' jadi true, '0' jadi false.
     */
    protected $casts = [
        'status' => 'boolean',
    ];
}

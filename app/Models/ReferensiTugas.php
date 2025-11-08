<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferensiTugas extends Model
{
    use HasFactory;

    // Nama tabelnya 'referensi_tugas' (singular 'tuga' aneh)
    protected $table = 'referensi_tugas';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'deskripsi_tugas',
        'status',
    ];

    /**
     * Tipe data asli untuk atribut.
     */
    protected $casts = [
        'status' => 'boolean',
    ];
}

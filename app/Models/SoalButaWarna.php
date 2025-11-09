<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalButaWarna extends Model
{
    use HasFactory;

    protected $table = 'soal_buta_warnas';

    protected $fillable = [
        'gambar_soal',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'pilihan_e',
        'jawaban_benar',
    ];
}

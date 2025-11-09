<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilButaWarna extends Model
{
    use HasFactory;

    protected $table = 'hasil_buta_warnas';

    protected $fillable = [
        'peserta_seleksi_id',
        'soal_buta_warna_id',
        'jawaban_peserta',
        'is_benar',
    ];

    public function pesertaSeleksi(): BelongsTo
    {
        return $this->belongsTo(PesertaSeleksi::class, 'peserta_seleksi_id');
    }

    public function soalButaWarna(): BelongsTo
    {
        return $this->belongsTo(SoalButaWarna::class, 'soal_buta_warna_id');
    }
}

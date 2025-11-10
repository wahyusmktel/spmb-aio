<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilTpa extends Model
{
    use HasFactory;

    protected $table = 'hasil_tpas';

    protected $fillable = [
        'peserta_seleksi_id',
        'tpa_soal_id',
        'jawaban_peserta',
        'is_benar',
    ];

    public function pesertaSeleksi(): BelongsTo
    {
        return $this->belongsTo(PesertaSeleksi::class, 'peserta_seleksi_id');
    }

    public function tpaSoal(): BelongsTo
    {
        return $this->belongsTo(TpaSoal::class, 'tpa_soal_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TpaSoal extends Model
{
    use HasFactory;

    protected $table = 'tpa_soals';

    protected $fillable = [
        'tpa_grup_soal_id',
        'pertanyaan_teks',
        'gambar_soal',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'pilihan_e',
        'jawaban_benar',
    ];

    // Satu Soal dimiliki SATU Grup
    public function tpaGrupSoal(): BelongsTo
    {
        return $this->belongsTo(TpaGrupSoal::class, 'tpa_grup_soal_id');
    }
}

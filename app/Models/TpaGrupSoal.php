<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TpaGrupSoal extends Model
{
    use HasFactory;

    protected $table = 'tpa_grup_soals';

    protected $fillable = [
        'nama_grup',
        'deskripsi',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    // Satu Grup punya BANYAK Soal
    public function tpaSoals(): HasMany
    {
        return $this->hasMany(TpaSoal::class, 'tpa_grup_soal_id');
    }

    public function jadwalSeleksis(): BelongsToMany
    {
        return $this->belongsToMany(JadwalSeleksi::class, 'jadwal_tpa_settings', 'tpa_grup_soal_id', 'jadwal_seleksi_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenugasanPetugas extends Model
{
    use HasFactory;

    // Tentukan nama tabelnya
    protected $table = 'penugasan_petugas';

    protected $fillable = [
        'id_jadwal_seleksi',
        'id_guru',
        'id_referensi_tugas',
        'kehadiran',
    ];

    // TAMBAHKAN/UPDATE $casts INI
    protected $casts = [
        'kehadiran' => 'boolean',
    ];

    // Relasi ke Jadwal
    public function jadwalSeleksi(): BelongsTo
    {
        return $this->belongsTo(JadwalSeleksi::class, 'id_jadwal_seleksi');
    }

    // Relasi ke Guru
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    // Relasi ke Tugas
    public function referensiTugas(): BelongsTo
    {
        return $this->belongsTo(ReferensiTugas::class, 'id_referensi_tugas');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PesertaSeleksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_jadwal_seleksi',
        'id_akun_cbt',
        'nomor_pendaftaran',
        'nama_pendaftar',
        'nomor_telepon',
        'kehadiran',
        'status_tes_buta_warna', // <-- TAMBAHKAN INI
        'nilai_tes_buta_warna', // <-- TAMBAHKAN INI
    ];

    // TAMBAHKAN/UPDATE $casts INI
    protected $casts = [
        'kehadiran' => 'boolean',
        'status_tes_buta_warna' => 'boolean', // <-- TAMBAHKAN INI
    ];

    // Relasi ke Jadwal
    public function jadwalSeleksi(): BelongsTo
    {
        return $this->belongsTo(JadwalSeleksi::class, 'id_jadwal_seleksi');
    }

    // Relasi ke Akun CBT
    public function akunCbt(): BelongsTo
    {
        return $this->belongsTo(ReferensiAkunCbt::class, 'id_akun_cbt');
    }

    // <-- TAMBAHKAN RELASI INI -->
    public function hasilButaWarna(): HasMany
    {
        return $this->hasMany(HasilButaWarna::class, 'peserta_seleksi_id');
    }
}

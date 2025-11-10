<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Tambahkan ini
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JadwalSeleksi extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'id_tahun_pelajaran',
        'judul_kegiatan',
        'tanggal_mulai_pelaksanaan',
        'tanggal_akhir_pelaksanaan',
        'lokasi_kegiatan',
        'nomor_surat_tugas',
        'kota_surat',
        'tanggal_surat',
        'id_penandatangan',
        'file_hadir_peserta', // <-- TAMBAHKAN INI
        'file_hadir_petugas', // <-- TAMBAHKAN INI
        'file_berita_acara', // <-- TAMBAHKAN INI
        'status', // <-- TAMBAHKAN
        'published_by_user_id', // <-- TAMBAHKAN
    ];

    /**
     * Tipe data asli untuk atribut.
     * PENTING untuk handling input date/datetime
     */
    protected $casts = [
        'tanggal_mulai_pelaksanaan' => 'datetime',
        'tanggal_akhir_pelaksanaan' => 'datetime',
        'tanggal_surat' => 'date',
    ];

    /**
     * RELASI BARU ke TahunPelajaran
     */
    public function tahunPelajaran(): BelongsTo
    {
        return $this->belongsTo(TahunPelajaran::class, 'id_tahun_pelajaran');
    }

    /**
     * Definisikan relasi ke User (Penandatangan)
     */
    public function penandatangan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_penandatangan');
    }

    public function penugasanPetugas(): HasMany
    {
        return $this->hasMany(PenugasanPetugas::class, 'id_jadwal_seleksi');
    }

    public function pesertaSeleksi(): HasMany
    {
        return $this->hasMany(PesertaSeleksi::class, 'id_jadwal_seleksi');
    }

    /**
     * Grup Soal TPA apa saja yang ditugaskan di jadwal ini.
     */
    public function tpaGrupSoals(): BelongsToMany
    {
        return $this->belongsToMany(TpaGrupSoal::class, 'jadwal_tpa_settings', 'jadwal_seleksi_id', 'tpa_grup_soal_id')
            ->withTimestamps()
            ->orderBy('urutan'); // (Jika pakai 'urutan')
    }
}

<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReferensiAkunCbt extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'id_tahun_pelajaran',
        'username',
        'password',
        'status',
    ];

    /**
     * Atribut yang harus disembunyikan.
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Tipe data asli untuk atribut.
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Relasi ke TahunPelajaran
     */
    public function tahunPelajaran(): BelongsTo
    {
        return $this->belongsTo(TahunPelajaran::class, 'id_tahun_pelajaran');
    }

    /**
     * === MUTATOR OTOMATIS HASH PASSWORD ===
     * Ini akan otomatis HASH password setiap kali kita simpan
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => Hash::make($value),
        );
    }

    public function pesertaSeleksi(): HasOne
    {
        return $this->hasOne(PesertaSeleksi::class, 'id_akun_cbt');
    }
}

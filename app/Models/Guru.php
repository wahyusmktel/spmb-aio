<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{

    protected $table = 'gurus';
    protected $fillable = [
        'nip',
        'nama_guru',
        'mata_pelajaran',
    ];

    public function penugasanPetugas(): HasMany
    {
        return $this->hasMany(PenugasanPetugas::class, 'id_guru');
    }
}

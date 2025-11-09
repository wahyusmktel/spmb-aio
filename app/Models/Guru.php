<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guru extends Model
{

    protected $table = 'gurus';
    protected $fillable = [
        'nip',
        'nama_guru',
        'email',
        'mata_pelajaran',
        'user_id'
    ];

    public function penugasanPetugas(): HasMany
    {
        return $this->hasMany(PenugasanPetugas::class, 'id_guru');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ekstrakurikuler extends Model
{
    protected $fillable = [
        'nama',
        'pembina',
        'deskripsi',
    ];

    public function siswas(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'siswa_ekstrakurikuler')
            ->withPivot('tahun_ajaran_id', 'keterangan')
            ->withTimestamps();
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataPelajaran extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'kkm',
        'jumlah_jam_per_minggu',
        'deskripsi',
        'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'is_aktif' => 'boolean',
        ];
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    // Relationships
    public function gurus(): BelongsToMany
    {
        return $this->belongsToMany(Guru::class, 'guru_mata_pelajaran')
            ->withPivot('tahun_ajaran_id')
            ->withTimestamps();
    }

    public function jadwalPelajarans(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    public function bobotNilais(): HasMany
    {
        return $this->hasMany(BobotNilai::class);
    }
}

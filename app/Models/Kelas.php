<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'tahun_ajaran_id',
        'jurusan_id',
        'wali_kelas_id',
        'tingkat',
        'nomor',
        'kapasitas',
    ];

    // Relationships
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class);
    }

    public function jadwalPelajarans(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    // Helpers
    public function getNamaKelasAttribute(): string
    {
        $jurusanKode = $this->jurusan ? $this->jurusan->kode : 'UMUM';
        return "{$this->tingkat}-{$jurusanKode}-{$this->nomor}";
    }

    public function getJumlahSiswaAttribute(): int
    {
        return $this->siswas()->count();
    }
}

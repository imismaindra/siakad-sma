<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    protected $fillable = [
        'nama',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'is_aktif' => 'boolean',
        ];
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    // Relationships
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
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

    // Helpers
    public function getSemesterLabelAttribute(): string
    {
        return $this->semester === '1' ? 'Ganjil' : 'Genap';
    }

    public function getNamaLengkapAttribute(): string
    {
        return "{$this->nama} Semester {$this->semester_label}";
    }
}

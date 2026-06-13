<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BobotNilai extends Model
{
    protected $fillable = [
        'mata_pelajaran_id',
        'tahun_ajaran_id',
        'kelas_id',
        'bobot_harian',
        'bobot_uts',
        'bobot_uas',
    ];

    protected function casts(): array
    {
        return [
            'bobot_harian' => 'float',
            'bobot_uts' => 'float',
            'bobot_uas' => 'float',
        ];
    }

    // Relationships
    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    // Validasi: total bobot harus = 100
    public function getTotalBobotAttribute(): float
    {
        return $this->bobot_harian + $this->bobot_uts + $this->bobot_uas;
    }
}

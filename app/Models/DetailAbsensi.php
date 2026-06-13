<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailAbsensi extends Model
{
    protected $fillable = [
        'absensi_id',
        'siswa_id',
        'status',
        'keterangan',
    ];

    // Relationships
    public function absensi(): BelongsTo
    {
        return $this->belongsTo(Absensi::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}

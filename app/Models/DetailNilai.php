<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailNilai extends Model
{
    protected $fillable = [
        'nilai_id',
        'judul',
        'jenis',
        'nilai',
        'tanggal',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'nilai' => 'float',
        ];
    }

    // Relationships
    public function nilai(): BelongsTo
    {
        return $this->belongsTo(Nilai::class);
    }
}

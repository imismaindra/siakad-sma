<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'konten',
        'target',
        'kelas_id',
        'is_aktif',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_aktif' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true)->whereNotNull('published_at');
    }

    // Relationships
    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
}

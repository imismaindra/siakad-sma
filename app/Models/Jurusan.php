<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    protected $fillable = [
        'nama',
        'kode',
    ];

    // Relationships
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Pengumuman extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('pengumuman')
            ->setDescriptionForEvent(fn (string $eventName) => "Pengumuman '{$this->judul}' telah di-{$eventName}");
    }

    protected $table = 'pengumumans';

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

    public function scopeForUser($query, User $user)
    {
        return $query->aktif()->where(function ($q) use ($user) {
            $q->where('target', 'semua');

            if ($user->isGuru()) {
                $q->orWhere('target', 'guru');
            } elseif ($user->isSiswa() && $user->siswa) {
                $q->orWhere('target', 'siswa');
                if ($user->siswa->kelas_id) {
                    $q->orWhere(function ($sub) use ($user) {
                        $sub->where('target', 'kelas')->where('kelas_id', $user->siswa->kelas_id);
                    });
                }
            } elseif ($user->isOrangTua() && $user->orangTua) {
                $q->orWhere('target', 'orang_tua');
                $kelasIds = $user->orangTua->siswas()->pluck('kelas_id')->filter()->unique();
                if ($kelasIds->isNotEmpty()) {
                    $q->orWhere(function ($sub) use ($kelasIds) {
                        $sub->where('target', 'kelas')->whereIn('kelas_id', $kelasIds);
                    });
                }
            }
        });
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

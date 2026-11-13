<?php

namespace App\Models;

use Carbon\Carbon;
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
        'gambar',
        'dokumen',
        'dokumen_nama',
        'target',
        'kelas_id',
        'is_aktif',
        'kirim_email',
        'published_at',
    ];

    protected $appends = [
        'gambar_url',
        'dokumen_url',
        'published_at_formatted',
    ];

    protected function casts(): array
    {
        return [
            'is_aktif' => 'boolean',
            'kirim_email' => 'boolean',
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

    // Accessors
    public function getGambarUrlAttribute(): ?string
    {
        if (! $this->gambar) {
            return null;
        }

        if (str_starts_with($this->gambar, 'http://') || str_starts_with($this->gambar, 'https://')) {
            return $this->gambar;
        }

        $cleanPath = ltrim($this->gambar, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        return asset('storage/' . $cleanPath);
    }

    public function getDokumenUrlAttribute(): ?string
    {
        if (! $this->dokumen) {
            return null;
        }

        if (str_starts_with($this->dokumen, 'http://') || str_starts_with($this->dokumen, 'https://')) {
            return $this->dokumen;
        }

        $cleanPath = ltrim($this->dokumen, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        return asset('storage/' . $cleanPath);
    }

    public function getPublishedAtFormattedAttribute(): ?string
    {
        return $this->published_at ? $this->published_at->locale('id')->isoFormat('D MMMM YYYY · HH:mm') . ' WIB' : null;
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

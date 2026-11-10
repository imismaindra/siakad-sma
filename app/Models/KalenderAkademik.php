<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class KalenderAkademik extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('kalender')
            ->setDescriptionForEvent(fn (string $eventName) => "Agenda kalender akademik '{$this->judul}' telah di-{$eventName}");
    }

    protected $table = 'kalender_akademiks';

    protected $fillable = [
        'tahun_ajaran_id',
        'judul',
        'deskripsi',
        'kategori',
        'tanggal_mulai',
        'tanggal_selesai',
        'warna',
        'target',
        'is_libur',
        'is_aktif',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'is_libur' => 'boolean',
            'is_aktif' => 'boolean',
        ];
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    public function scopeBulan($query, int $bulan, int $tahun)
    {
        $start = Carbon::create($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $end = Carbon::create($tahun, $bulan, 1)->endOfMonth()->toDateString();

        return $query->where(function ($q) use ($start, $end) {
            $q->whereBetween('tanggal_mulai', [$start, $end])
                ->orWhereBetween('tanggal_selesai', [$start, $end])
                ->orWhere(function ($sub) use ($start, $end) {
                    $sub->where('tanggal_mulai', '<=', $start)
                        ->where('tanggal_selesai', '>=', $end);
                });
        });
    }

    public function scopeForUser($query, User $user)
    {
        return $query->aktif()->where(function ($q) use ($user) {
            $q->where('target', 'semua');

            if ($user->isGuru()) {
                $q->orWhere('target', 'guru');
            } elseif ($user->isSiswa()) {
                $q->orWhere('target', 'siswa');
            } elseif ($user->isOrangTua()) {
                $q->orWhere('target', 'orang_tua');
            }
        });
    }

    // Helpers
    public function getLabelKategoriAttribute(): string
    {
        return match ($this->kategori) {
            'libur' => 'Libur Sekolah',
            'ujian' => 'Ujian / Asesmen',
            'rapor' => 'Pembagian Rapor',
            'kegiatan' => 'Kegiatan Sekolah',
            default => 'Agenda Khusus',
        };
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->kategori) {
            'libur' => 'bg-rose-50 text-rose-700 border-rose-200 ring-rose-500/20',
            'ujian' => 'bg-amber-50 text-amber-800 border-amber-200 ring-amber-500/20',
            'rapor' => 'bg-purple-50 text-purple-700 border-purple-200 ring-purple-500/20',
            'kegiatan' => 'bg-blue-50 text-blue-700 border-blue-200 ring-blue-500/20',
            default => 'bg-slate-100 text-slate-700 border-slate-200 ring-slate-500/20',
        };
    }

    public function getDotColorClassAttribute(): string
    {
        return match ($this->kategori) {
            'libur' => 'bg-rose-500',
            'ujian' => 'bg-amber-500',
            'rapor' => 'bg-purple-500',
            'kegiatan' => 'bg-blue-500',
            default => 'bg-slate-500',
        };
    }

    // Relationships
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

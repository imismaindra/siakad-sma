<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Absensi extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('absensi')
            ->setDescriptionForEvent(fn (string $eventName) => "Sesi absensi kelas ID #{$this->kelas_id} tanggal {$this->tanggal?->format('d/m/Y')} di-{$eventName}");
    }
    protected $fillable = [
        'jadwal_pelajaran_id',
        'kelas_id',
        'guru_id',
        'mata_pelajaran_id',
        'tanggal',
        'materi',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    // Relationships
    public function jadwalPelajaran(): BelongsTo
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function detailAbsensis(): HasMany
    {
        return $this->hasMany(DetailAbsensi::class);
    }

    // Helper: Rekap absensi sesi ini
    public function getRekapAttribute(): array
    {
        $detail = $this->detailAbsensis()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'hadir' => $detail['hadir'] ?? 0,
            'sakit' => $detail['sakit'] ?? 0,
            'izin'  => $detail['izin'] ?? 0,
            'alpa'  => $detail['alpa'] ?? 0,
        ];
    }
}

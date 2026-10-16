<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DetailAbsensi extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('absensi')
            ->setDescriptionForEvent(fn (string $eventName) => "Status kehadiran siswa ID #{$this->siswa_id} di-{$eventName} (Status: {$this->status})");
    }

    protected $fillable = [
        'absensi_id',
        'siswa_id',
        'status',
        'metode',
        'waktu_absen',
        'jarak_meter',
        'akurasi_meter',
        'lokasi_valid',
        'alasan_override',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'waktu_absen' => 'datetime',
            'lokasi_valid' => 'boolean',
            'jarak_meter' => 'float',
            'akurasi_meter' => 'float',
        ];
    }

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

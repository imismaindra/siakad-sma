<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DetailNilai extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('nilai')
            ->setDescriptionForEvent(fn (string $eventName) => "Komponen nilai '{$this->judul}' ({$this->jenis}) untuk nilai ID #{$this->nilai_id} di-{$eventName}");
    }
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

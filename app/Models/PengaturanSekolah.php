<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PengaturanSekolah extends Model
{
    use LogsActivity;

    protected $table = 'pengaturan_sekolahs';

    protected $fillable = [
        'nama_sekolah',
        'alamat',
        'latitude',
        'longitude',
        'radius_meter',
        'max_accuracy_meter',
        'is_geofencing_active',
        'enforce_anti_mock',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'radius_meter' => 'integer',
            'max_accuracy_meter' => 'integer',
            'is_geofencing_active' => 'boolean',
            'enforce_anti_mock' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('pengaturan')
            ->setDescriptionForEvent(fn (string $eventName) => "Pengaturan lokasi sekolah di-{$eventName}");
    }

    /**
     * Mengambil pengaturan sekolah tunggal (Singleton pattern dengan cache).
     */
    public static function getSettings(): self
    {
        return Cache::rememberForever('pengaturan_sekolah', function () {
            return self::firstOrCreate(
                ['id' => 1],
                [
                    'nama_sekolah' => 'SMA Negeri 1',
                    'alamat' => 'Jl. Pendidikan No. 1, Kota Bandung',
                    'latitude' => -6.9147440,
                    'longitude' => 107.6098100,
                    'radius_meter' => 100,
                    'max_accuracy_meter' => 80,
                    'is_geofencing_active' => true,
                    'enforce_anti_mock' => true,
                ]
            );
        });
    }

    /**
     * Membersihkan cache pengaturan sekolah.
     */
    public static function clearCache(): void
    {
        Cache::forget('pengaturan_sekolah');
    }
}

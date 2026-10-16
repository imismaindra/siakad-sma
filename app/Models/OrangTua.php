<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OrangTua extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'orang_tuas';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'jenis_kelamin',
        'no_telepon',
        'pekerjaan',
        'alamat',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('orang_tua')
            ->setDescriptionForEvent(fn (string $eventName) => "Data profil orang tua {$this->nama_lengkap} di-{$eventName}");
    }

    // ─────────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function siswas(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'orang_tua_siswa')
            ->withPivot('hubungan')
            ->withTimestamps();
    }
}

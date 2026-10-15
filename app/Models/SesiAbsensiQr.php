<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SesiAbsensiQr extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'jadwal_pelajaran_id',
        'absensi_id',
        'guru_id',
        'tanggal',
        'dibuka_pada',
        'ditutup_pada',
        'secret',
        'durasi_token_detik',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'dibuka_pada' => 'datetime',
            'ditutup_pada' => 'datetime',
            'durasi_token_detik' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('absensi')
            ->setDescriptionForEvent(fn (string $eventName) => "Sesi absensi QR #{$this->id} ({$this->jadwalPelajaran?->mataPelajaran?->nama}) di-{$eventName}");
    }

    // ─────────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────────

    public function jadwalPelajaran(): BelongsTo
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function absensi(): BelongsTo
    {
        return $this->belongsTo(Absensi::class);
    }

    // ─────────────────────────────────────────────
    // QR Token Generation & Verification (F1-02, F1-03)
    // ─────────────────────────────────────────────

    /**
     * Menghasilkan token dinamis berbasis waktu (time-window HMAC).
     */
    public function generateToken(?int $timestamp = null): string
    {
        $durasi = max(10, $this->durasi_token_detik ?: 30);
        $window = (int) floor(($timestamp ?? now()->timestamp) / $durasi);

        return hash_hmac('sha256', "sesi:{$this->id}:window:{$window}", $this->secret);
    }

    /**
     * Memvalidasi token dengan toleransi 1 window sebelumnya (grace period toleransi latensi jaringan).
     */
    public function isValidToken(string $token): bool
    {
        if ($this->status !== 'aktif') {
            return false;
        }

        $durasi = max(10, $this->durasi_token_detik ?: 30);
        $current = (int) floor(now()->timestamp / $durasi);
        $prev = $current - 1;

        $validCurrent = hash_hmac('sha256', "sesi:{$this->id}:window:{$current}", $this->secret);
        $validPrev = hash_hmac('sha256', "sesi:{$this->id}:window:{$prev}", $this->secret);

        return hash_equals($validCurrent, $token) || hash_equals($validPrev, $token);
    }

    /**
     * Hitung sisa detik sebelum token saat ini berganti.
     */
    public function getTimeRemaining(): int
    {
        $durasi = max(10, $this->durasi_token_detik ?: 30);
        $elapsed = now()->timestamp % $durasi;

        return $durasi - $elapsed;
    }

    /**
     * Payload terenkod yang dimasukkan ke dalam QR Code.
     */
    public function getQrPayload(): string
    {
        return json_encode([
            'sesi_id' => $this->id,
            'token' => $this->generateToken(),
        ]);
    }
}

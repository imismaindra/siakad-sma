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
     * Menghasilkan kode OTP rolling 6-digit untuk input manual cadangan.
     */
    public function getRollingCode(?int $timestamp = null): string
    {
        $durasi = max(10, $this->durasi_token_detik ?: 30);
        $window = (int) floor(($timestamp ?? now()->timestamp) / $durasi);
        $hash = hash_hmac('sha256', "otp:sesi:{$this->id}:w:{$window}", $this->secret);

        return strtoupper(substr($hash, 0, 6));
    }

    /**
     * Validasi kode OTP rolling 6-digit dengan toleransi 1 window sebelumnya.
     */
    public function isValidRollingCode(string $code): bool
    {
        if ($this->status !== 'aktif') {
            return false;
        }

        $durasi = max(10, $this->durasi_token_detik ?: 30);
        $current = (int) floor(now()->timestamp / $durasi);
        $prev = $current - 1;

        $validCurrent = strtoupper(substr(hash_hmac('sha256', "otp:sesi:{$this->id}:w:{$current}", $this->secret), 0, 6));
        $validPrev = strtoupper(substr(hash_hmac('sha256', "otp:sesi:{$this->id}:w:{$prev}", $this->secret), 0, 6));

        $cleanCode = strtoupper(trim($code));

        return hash_equals($validCurrent, $cleanCode) || hash_equals($validPrev, $cleanCode);
    }

    /**
     * Menghasilkan ticket terenkripsi kuat (AES-256-CBC + HMAC) yang kompak dan ramah QR code (~85 karakter).
     * Tidak membocorkan integer ID, anti-tamper, dan kedaluwarsa otomatis.
     */
    public function generateEncryptedTicket(?int $timestamp = null): string
    {
        $durasi = max(10, $this->durasi_token_detik ?: 30);
        $ts = $timestamp ?? now()->timestamp;
        $window = (int) floor($ts / $durasi);
        $nonce = bin2hex(random_bytes(4)); // 8 hex chars

        $plain = "{$this->id}:{$window}:{$nonce}";
        $key = hash('sha256', config('app.key'), true);
        $mac = substr(hash_hmac('sha256', $plain, $key . $this->secret), 0, 16);
        $payload = "{$plain}:{$mac}";

        $iv = random_bytes(16);
        $cipher = openssl_encrypt($payload, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        return rtrim(strtr(base64_encode($iv . $cipher), '+/', '-_'), '=');
    }

    /**
     * Mendekripsi tiket terenkripsi (mendukung format kompak dan legacy).
     */
    public static function decryptTicket(string $rawTicket): ?array
    {
        try {
            if (str_contains($rawTicket, 'qr=')) {
                $queryString = parse_url($rawTicket, PHP_URL_QUERY);
                if ($queryString) {
                    parse_str($queryString, $queryParams);
                    $rawTicket = $queryParams['qr'] ?? $rawTicket;
                }
            }

            $rawTicket = trim($rawTicket);
            $key = hash('sha256', config('app.key'), true);

            $raw = base64_decode(strtr($rawTicket, '-_', '+/'));
            if ($raw && strlen($raw) > 16) {
                $iv = substr($raw, 0, 16);
                $cipher = substr($raw, 16);
                $decrypted = openssl_decrypt($cipher, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

                if ($decrypted) {
                    $parts = explode(':', $decrypted);
                    if (count($parts) === 4) {
                        return [
                            'sesi_id' => (int) $parts[0],
                            'window' => (int) $parts[1],
                            'nonce' => $parts[2],
                            'mac' => $parts[3],
                        ];
                    }
                }
            }

            // Fallback Crypt::decryptString untuk backward-compatibility
            try {
                $json = \Illuminate\Support\Facades\Crypt::decryptString($rawTicket);
                $data = json_decode($json, true);
                if (is_array($data) && isset($data['s'], $data['w'])) {
                    return [
                        'sesi_id' => (int) $data['s'],
                        'window' => (int) $data['w'],
                        'nonce' => $data['nonce'] ?? '',
                        'mac' => $data['sig'] ?? '',
                        'exp' => $data['exp'] ?? null,
                    ];
                }
            } catch (\Throwable $e) {}

            return null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Memvalidasi keabsahan data tiket yang sudah didekripsi.
     */
    public function isValidTicketPayload(array $payload): bool
    {
        if ($this->status !== 'aktif') {
            return false;
        }

        $sesiId = $payload['sesi_id'] ?? $payload['s'] ?? null;
        $window = $payload['window'] ?? $payload['w'] ?? null;
        $nonce = $payload['nonce'] ?? null;
        $mac = $payload['mac'] ?? $payload['sig'] ?? null;

        if ($sesiId === null || $window === null || $nonce === null || $mac === null) {
            return false;
        }

        if ((int) $sesiId !== $this->id) {
            return false;
        }

        $durasi = max(10, $this->durasi_token_detik ?: 30);
        $current = (int) floor(now()->timestamp / $durasi);
        $ticketWindow = (int) $window;

        if ($ticketWindow !== $current && $ticketWindow !== ($current - 1)) {
            return false;
        }

        $key = hash('sha256', config('app.key'), true);
        $expectedMac = substr(hash_hmac('sha256', "{$this->id}:{$ticketWindow}:{$nonce}", $key . $this->secret), 0, 16);

        if (hash_equals($expectedMac, (string) $mac)) {
            return true;
        }

        $legacySig = substr(hash_hmac('sha256', "sesi:{$this->id}:w:{$ticketWindow}:{$nonce}", $this->secret), 0, 16);
        return hash_equals($legacySig, (string) $mac);
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
     * URL generic presensi yang dimasukkan ke dalam QR Code.
     * Ketika dipindai kamera ponsel biasa, akan langsung membuka halaman presensi di browser.
     * Jika dibuka di localhost, otomatis menggunakan IP LAN laptop agar dapat diakses dari HP.
     */
    public function getQrUrl(): string
    {
        $url = route('siswa.absensi.scan', [
            'qr' => $this->generateEncryptedTicket(),
        ]);

        try {
            $host = request()->getHost();
            if (in_array($host, ['localhost', '127.0.0.1', '::1', ''])) {
                $lanIp = gethostbyname(gethostname());
                if ($lanIp && $lanIp !== '127.0.0.1' && ! str_starts_with($lanIp, '127.')) {
                    $port = request()->getPort();
                    $targetHost = $port && $port != 80 && $port != 443 ? "{$lanIp}:{$port}" : $lanIp;
                    $scheme = request()->getScheme() ?: 'http';
                    $url = preg_replace('#^https?://[^/]+#', "{$scheme}://{$targetHost}", $url);
                }
            }
        } catch (\Throwable $e) {}

        return $url;
    }

    /**
     * Payload terenkod yang dimasukkan ke dalam QR Code.
     */
    public function getQrPayload(): string
    {
        return $this->getQrUrl();
    }
}

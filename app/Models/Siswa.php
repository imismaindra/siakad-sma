<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $fillable = [
        'user_id',
        'kelas_id',
        'nis',
        'nisn',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'no_telepon_ortu',
        'nama_ortu',
        'status',
        'foto',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function detailAbsensis(): HasMany
    {
        return $this->hasMany(DetailAbsensi::class);
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    // Helpers
    public function getUmurAttribute(): ?int
    {
        return $this->tanggal_lahir
            ? $this->tanggal_lahir->diffInYears(now())
            : null;
    }

    /**
     * Hitung total ketidakhadiran (sakit, izin, alpa) dalam satu tahun ajaran.
     */
    public function rekapAbsensi(int $tahunAjaranId): array
    {
        $data = $this->detailAbsensis()
            ->whereHas('absensi', fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaranId)
                ->whereHas('jadwalPelajaran', fn ($jq) => $jq->where('tahun_ajaran_id', $tahunAjaranId)))
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'hadir' => $data['hadir'] ?? 0,
            'sakit' => $data['sakit'] ?? 0,
            'izin'  => $data['izin'] ?? 0,
            'alpa'  => $data['alpa'] ?? 0,
        ];
    }
}

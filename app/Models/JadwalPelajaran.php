<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalPelajaran extends Model
{
    protected $fillable = [
        'kelas_id',
        'mata_pelajaran_id',
        'guru_id',
        'tahun_ajaran_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'urutan_jam',
        'ruangan',
    ];

    // Relationships
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    // Helper: cek apakah guru sudah dijadwalkan di waktu yang sama (konflik)
    public static function cekKonflikGuru(
        int $guruId,
        string $hari,
        string $jamMulai,
        string $jamSelesai,
        int $tahunAjaranId,
        ?int $excludeId = null
    ): bool {
        $query = self::where('guru_id', $guruId)
            ->where('hari', $hari)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                // Overlap: existing.jam_mulai < new.jam_selesai AND existing.jam_selesai > new.jam_mulai
                $q->where('jam_mulai', '<', $jamSelesai)
                    ->where('jam_selesai', '>', $jamMulai);
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nilai extends Model
{
    protected $fillable = [
        'siswa_id',
        'mata_pelajaran_id',
        'kelas_id',
        'guru_id',
        'tahun_ajaran_id',
        'rata_rata_harian',
        'nilai_uts',
        'nilai_uas',
        'nilai_akhir',
        'deskripsi',
        'is_final',
    ];

    protected function casts(): array
    {
        return [
            'rata_rata_harian' => 'float',
            'nilai_uts' => 'float',
            'nilai_uas' => 'float',
            'nilai_akhir' => 'float',
            'is_final' => 'boolean',
        ];
    }

    // Scopes
    public function scopeFinal($query)
    {
        return $query->where('is_final', true);
    }

    // Relationships
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function detailNilais(): HasMany
    {
        return $this->hasMany(DetailNilai::class);
    }

    /**
     * Kalkulasi ulang rata-rata harian dari detail_nilais dan hitung nilai_akhir berdasarkan bobot.
     */
    public function hitungNilaiAkhir(): void
    {
        // Hitung rata-rata harian dari detail nilai
        $detailHarian = $this->detailNilais()->avg('nilai');
        $this->rata_rata_harian = $detailHarian ?? $this->rata_rata_harian;

        // Ambil bobot
        $bobot = BobotNilai::where('mata_pelajaran_id', $this->mata_pelajaran_id)
            ->where('tahun_ajaran_id', $this->tahun_ajaran_id)
            ->where(function ($q) {
                $q->where('kelas_id', $this->kelas_id)->orWhereNull('kelas_id');
            })
            ->orderByRaw('kelas_id IS NULL ASC') // Prioritas bobot per kelas
            ->first();

        $bobotHarian = $bobot ? $bobot->bobot_harian : 40;
        $bobotUts = $bobot ? $bobot->bobot_uts : 30;
        $bobotUas = $bobot ? $bobot->bobot_uas : 30;

        // Hitung nilai akhir
        $nilaiAkhir = 0;
        $totalBobot = 0;

        if ($this->rata_rata_harian !== null) {
            $nilaiAkhir += ($this->rata_rata_harian * $bobotHarian / 100);
            $totalBobot += $bobotHarian;
        }
        if ($this->nilai_uts !== null) {
            $nilaiAkhir += ($this->nilai_uts * $bobotUts / 100);
            $totalBobot += $bobotUts;
        }
        if ($this->nilai_uas !== null) {
            $nilaiAkhir += ($this->nilai_uas * $bobotUas / 100);
            $totalBobot += $bobotUas;
        }

        // Normalisasi jika total bobot < 100 (ada komponen yang null)
        if ($totalBobot > 0 && $totalBobot < 100) {
            $nilaiAkhir = ($nilaiAkhir / $totalBobot) * 100;
        }

        $this->nilai_akhir = round($nilaiAkhir, 2);
        $this->save();
    }

    // Helper
    public function getPredikatAttribute(): string
    {
        $na = $this->nilai_akhir ?? 0;
        if ($na >= 90) return 'A';
        if ($na >= 80) return 'B';
        if ($na >= 70) return 'C';
        if ($na >= 60) return 'D';
        return 'E';
    }

    public function getTuntasAttribute(): bool
    {
        $kkm = $this->mataPelajaran->kkm ?? 75;
        return ($this->nilai_akhir ?? 0) >= $kkm;
    }
}

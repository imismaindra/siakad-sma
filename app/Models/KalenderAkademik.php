<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class KalenderAkademik extends Model
{
    use LogsActivity, SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('kalender')
            ->setDescriptionForEvent(fn (string $eventName) => "Agenda kalender akademik '{$this->judul}' telah di-{$eventName}");
    }

    protected $table = 'kalender_akademiks';

    protected $fillable = [
        'tahun_ajaran_id',
        'judul',
        'deskripsi',
        'dokumen',
        'dokumen_nama',
        'kategori',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'warna',
        'target',
        'is_libur',
        'is_aktif',
        'lewati_akhir_pekan',
        'created_by',
    ];

    protected $appends = [
        'label_kategori',
        'badge_class',
        'dot_color_class',
        'tanggal_mulai_input',
        'tanggal_selesai_input',
        'tanggal_mulai_formatted',
        'tanggal_selesai_formatted',
        'periode_formatted',
        'waktu_formatted',
        'rentang_lengkap_formatted',
        'dokumen_url',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date:Y-m-d',
            'tanggal_selesai' => 'date:Y-m-d',
            'is_libur' => 'boolean',
            'is_aktif' => 'boolean',
            'lewati_akhir_pekan' => 'boolean',
        ];
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    public function scopeBulan($query, int $bulan, int $tahun)
    {
        $start = Carbon::create($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $end = Carbon::create($tahun, $bulan, 1)->endOfMonth()->toDateString();

        return $query->where(function ($q) use ($start, $end) {
            $q->whereBetween('tanggal_mulai', [$start, $end])
                ->orWhereBetween('tanggal_selesai', [$start, $end])
                ->orWhere(function ($sub) use ($start, $end) {
                    $sub->where('tanggal_mulai', '<=', $start)
                        ->where('tanggal_selesai', '>=', $end);
                });
        });
    }

    public function scopeForUser($query, User $user)
    {
        return $query->aktif()->where(function ($q) use ($user) {
            $q->where('target', 'semua');

            if ($user->isGuru()) {
                $q->orWhere('target', 'guru');
            } elseif ($user->isSiswa()) {
                $q->orWhere('target', 'siswa');
            } elseif ($user->isOrangTua()) {
                $q->orWhere('target', 'orang_tua');
            }
        });
    }

    // Accessors
    public function getLabelKategoriAttribute(): string
    {
        return match ($this->kategori) {
            'libur' => 'Libur Sekolah',
            'ujian' => 'Ujian / Asesmen',
            'rapor' => 'Pembagian Rapor',
            'kegiatan' => 'Kegiatan Sekolah',
            default => 'Agenda Khusus',
        };
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->kategori) {
            'libur' => 'bg-rose-50 text-rose-700 border-rose-200 ring-rose-500/20',
            'ujian' => 'bg-amber-50 text-amber-800 border-amber-200 ring-amber-500/20',
            'rapor' => 'bg-purple-50 text-purple-700 border-purple-200 ring-purple-500/20',
            'kegiatan' => 'bg-blue-50 text-blue-700 border-blue-200 ring-blue-500/20',
            default => 'bg-slate-100 text-slate-700 border-slate-200 ring-slate-500/20',
        };
    }

    public function getDotColorClassAttribute(): string
    {
        return match ($this->kategori) {
            'libur' => 'bg-rose-500',
            'ujian' => 'bg-amber-500',
            'rapor' => 'bg-purple-500',
            'kegiatan' => 'bg-blue-500',
            default => 'bg-slate-500',
        };
    }

    public function getTanggalMulaiInputAttribute(): ?string
    {
        return $this->tanggal_mulai ? Carbon::parse($this->tanggal_mulai)->format('Y-m-d') : null;
    }

    public function getTanggalSelesaiInputAttribute(): ?string
    {
        return $this->tanggal_selesai ? Carbon::parse($this->tanggal_selesai)->format('Y-m-d') : null;
    }

    public function getTanggalMulaiFormattedAttribute(): ?string
    {
        return $this->tanggal_mulai ? Carbon::parse($this->tanggal_mulai)->locale('id')->isoFormat('D MMMM YYYY') : null;
    }

    public function getTanggalSelesaiFormattedAttribute(): ?string
    {
        return $this->tanggal_selesai ? Carbon::parse($this->tanggal_selesai)->locale('id')->isoFormat('D MMMM YYYY') : null;
    }

    public function getWaktuFormattedAttribute(): string
    {
        if ($this->jam_mulai && $this->jam_selesai) {
            return substr($this->jam_mulai, 0, 5) . ' - ' . substr($this->jam_selesai, 0, 5) . ' WIB';
        }

        if ($this->jam_mulai) {
            return substr($this->jam_mulai, 0, 5) . ' WIB';
        }

        return 'Seharian Penuh';
    }

    public function getPeriodeFormattedAttribute(): string
    {
        if (! $this->tanggal_mulai) {
            return '-';
        }

        $mulai = Carbon::parse($this->tanggal_mulai);
        $selesai = $this->tanggal_selesai ? Carbon::parse($this->tanggal_selesai) : null;

        if ($selesai && $mulai->toDateString() !== $selesai->toDateString()) {
            if ($mulai->year === $selesai->year) {
                return $mulai->locale('id')->isoFormat('D MMM') . ' - ' . $selesai->locale('id')->isoFormat('D MMM YYYY');
            }
            return $mulai->locale('id')->isoFormat('D MMM YYYY') . ' - ' . $selesai->locale('id')->isoFormat('D MMM YYYY');
        }

        return $mulai->locale('id')->isoFormat('dddd, D MMMM YYYY');
    }

    public function getRentangLengkapFormattedAttribute(): string
    {
        $periode = $this->periode_formatted;
        $waktu = $this->waktu_formatted;

        if ($this->jam_mulai) {
            return "{$periode} ({$waktu})";
        }

        return $periode;
    }

    public function getDokumenUrlAttribute(): ?string
    {
        return $this->dokumen ? asset('storage/' . $this->dokumen) : null;
    }

    // Relationships
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

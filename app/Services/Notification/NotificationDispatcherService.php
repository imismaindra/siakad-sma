<?php

namespace App\Services\Notification;

use App\Models\DetailAbsensi;
use App\Models\Kelas;
use App\Models\Pengumuman;
use App\Models\SesiAbsensiQr;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Notifications\AlpaPresensiNotification;
use App\Notifications\NilaiTerbitNotification;
use App\Notifications\PengumumanBaruNotification;
use App\Notifications\SesiQrDibukaNotification;
use Illuminate\Support\Facades\Notification;

class NotificationDispatcherService
{
    /**
     * Kirim notifikasi alpa kepada Orang Tua dan Siswa bersangkutan.
     */
    public function dispatchAlpa(DetailAbsensi $detailAbsensi): void
    {
        $siswa = $detailAbsensi->siswa;
        if (! $siswa) {
            return;
        }

        $absensi = $detailAbsensi->absensi;
        $mapel = $absensi?->jadwalPelajaran?->mataPelajaran?->nama_pelajaran ?? 'Pelajaran Terjadwal';
        $tanggal = $absensi?->tanggal?->toDateString() ?? now()->toDateString();

        $recipients = collect();

        // 1. Wali murid / Orang tua terhubung
        $orangTuas = $siswa->orangTuas()->with('user')->get();
        foreach ($orangTuas as $ortu) {
            if ($ortu->user && $ortu->user->is_active) {
                $recipients->push($ortu->user);
            }
        }

        // 2. Akun siswa sendiri (jika punya user aktif)
        if ($siswa->user && $siswa->user->is_active) {
            $recipients->push($siswa->user);
        }

        $recipients = $recipients->unique('id');

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new AlpaPresensiNotification($detailAbsensi, $siswa, $mapel, $tanggal));
        }
    }

    /**
     * Kirim notifikasi pengumuman baru sesuai target audiens.
     */
    public function dispatchPengumuman(Pengumuman $pengumuman): void
    {
        if (! $pengumuman->is_aktif || ! $pengumuman->published_at) {
            return;
        }

        $query = User::aktif();

        switch ($pengumuman->target) {
            case 'guru':
                $query->role('guru');
                break;

            case 'siswa':
                $query->role('siswa');
                break;

            case 'orang_tua':
                $query->role('orang_tua');
                break;

            case 'kelas':
                if ($pengumuman->kelas_id) {
                    $siswaUserIds = Siswa::where('kelas_id', $pengumuman->kelas_id)
                        ->whereNotNull('user_id')
                        ->pluck('user_id');

                    $ortuUserIds = User::role('orang_tua')
                        ->whereHas('orangTua.siswas', function ($q) use ($pengumuman) {
                            $q->where('kelas_id', $pengumuman->kelas_id);
                        })
                        ->pluck('id');

                    $query->whereIn('id', $siswaUserIds->merge($ortuUserIds)->unique());
                }
                break;

            case 'semua':
            default:
                // Seluruh pengguna terdaftar
                break;
        }

        // Kecualikan pembuat pengumuman
        $recipients = $query->where('id', '!=', $pengumuman->user_id)->get();

        if ($recipients->isNotEmpty()) {
            try {
                Notification::send($recipients, new PengumumanBaruNotification($pengumuman));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("Gagal mengirim notifikasi pengumuman: " . $e->getMessage());
            }
        }
    }

    /**
     * Kirim notifikasi saat nilai dan e-rapor kelas difinalisasi.
     */
    public function dispatchNilaiTerbit(Kelas $kelas, ?TahunAjaran $tahunAjaran = null): void
    {
        $siswas = $kelas->siswas()->with(['user', 'orangTuas.user'])->get();

        foreach ($siswas as $siswa) {
            $recipients = collect();

            if ($siswa->user && $siswa->user->is_active) {
                $recipients->push($siswa->user);
            }

            foreach ($siswa->orangTuas as $ortu) {
                if ($ortu->user && $ortu->user->is_active) {
                    $recipients->push($ortu->user);
                }
            }

            $recipients = $recipients->unique('id');

            if ($recipients->isNotEmpty()) {
                Notification::send($recipients, new NilaiTerbitNotification($siswa, $tahunAjaran));
            }
        }
    }

    /**
     * Kirim notifikasi saat sesi presensi QR dibuka oleh guru ke siswa kelas terkait.
     */
    public function dispatchSesiQr(SesiAbsensiQr $sesiQr): void
    {
        $jadwal = $sesiQr->jadwalPelajaran;
        if (! $jadwal || ! $jadwal->kelas_id) {
            return;
        }

        $siswaUserIds = Siswa::where('kelas_id', $jadwal->kelas_id)
            ->whereNotNull('user_id')
            ->pluck('user_id');

        $recipients = User::aktif()->whereIn('id', $siswaUserIds)->get();

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new SesiQrDibukaNotification($sesiQr, $jadwal));
        }
    }
}

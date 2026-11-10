<?php

namespace App\Notifications;

use App\Models\DetailAbsensi;
use App\Models\Siswa;
use App\Services\Notification\Channels\TelegramChannel;
use App\Services\Notification\Channels\WhatsAppChannel;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlpaPresensiNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public DetailAbsensi $detailAbsensi;
    public Siswa $siswa;
    public string $mapel;
    public string $tanggal;

    public function __construct(DetailAbsensi $detailAbsensi, Siswa $siswa, string $mapel, string $tanggal)
    {
        $this->detailAbsensi = $detailAbsensi;
        $this->siswa = $siswa;
        $this->mapel = $mapel;
        $this->tanggal = $tanggal;
    }

    public function via(object $notifiable): array
    {
        $settings = method_exists($notifiable, 'getOrCreateNotificationSetting')
            ? $notifiable->getOrCreateNotificationSetting()
            : null;

        if ($settings && ! $settings->notify_alpa) {
            return [];
        }

        $channels = ['database'];

        if ($settings) {
            if ($settings->channel_mail && ! empty($notifiable->email)) {
                $channels[] = 'mail';
            }
            if ($settings->channel_telegram && ! empty($notifiable->routeNotificationForTelegram())) {
                $channels[] = TelegramChannel::class;
            }
            if ($settings->channel_whatsapp && ! empty($notifiable->routeNotificationForWhatsApp())) {
                $channels[] = WhatsAppChannel::class;
            }
        }

        return $channels;
    }

    public function toArray(object $notifiable): array
    {
        $tglFormatted = Carbon::parse($this->tanggal)->locale('id')->isoFormat('D MMMM YYYY');

        return [
            'type' => 'absensi_alpa',
            'title' => 'Pemberitahuan Ketidakhadiran (Alpa)',
            'message' => "Siswa {$this->siswa->nama_lengkap} (Kelas {$this->siswa->kelas?->nama_kelas}) tercatat Alpa pada mata pelajaran {$this->mapel} tanggal {$tglFormatted}.",
            'siswa_id' => $this->siswa->id,
            'siswa_nama' => $this->siswa->nama_lengkap,
            'mapel' => $this->mapel,
            'tanggal' => $this->tanggal,
            'url' => $notifiable->isOrangTua() ? route('orang-tua.absensi') : route('siswa.absensi'),
            'icon' => 'exclamation-circle',
            'color' => 'rose',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $tglFormatted = Carbon::parse($this->tanggal)->locale('id')->isoFormat('D MMMM YYYY');

        return (new MailMessage)
            ->subject("[SIAKAD SMA] Pemberitahuan Ketidakhadiran: {$this->siswa->nama_lengkap}")
            ->greeting("Yth. {$notifiable->name},")
            ->line("Kami menginformasikan bahwa siswa **{$this->siswa->nama_lengkap}** (NISN: {$this->siswa->nisn}, Kelas: {$this->siswa->kelas?->nama_kelas}) tercatat **Tidak Hadir Tanpa Keterangan (Alpa)** pada:")
            ->line("- **Mata Pelajaran**: {$this->mapel}")
            ->line("- **Tanggal**: {$tglFormatted}")
            ->line("Apabila terdapat kekeliruan atau kendala, mohon segera menghubungi pihak sekolah atau wali kelas.")
            ->action('Cek Riwayat Kehadiran', $notifiable->isOrangTua() ? route('orang-tua.absensi') : route('siswa.absensi'))
            ->salutation("Salam Hangat,\nTim Akademik SIAKAD SMA");
    }

    public function toTelegram(object $notifiable): string
    {
        $tglFormatted = Carbon::parse($this->tanggal)->locale('id')->isoFormat('D MMMM YYYY');

        return "⚠️ *Pemberitahuan Ketidakhadiran (Alpa)*\n\n"
            . "Siswa: *{$this->siswa->nama_lengkap}*\n"
            . "Kelas: *{$this->siswa->kelas?->nama_kelas}*\n"
            . "Mata Pelajaran: *{$this->mapel}*\n"
            . "Tanggal: *{$tglFormatted}*\n\n"
            . "Status presensi tercatat *ALPA*. Silakan hubungi wali kelas jika ada kekeliruan.\n"
            . "— _SIAKAD SMA Terpadu_";
    }

    public function toWhatsApp(object $notifiable): string
    {
        $tglFormatted = Carbon::parse($this->tanggal)->locale('id')->isoFormat('D MMMM YYYY');

        return "*[SIAKAD SMA] Pemberitahuan Ketidakhadiran*\n\n"
            . "Yth. {$notifiable->name},\n"
            . "Siswa *{$this->siswa->nama_lengkap}* (Kelas {$this->siswa->kelas?->nama_kelas}) tercatat *ALPA* pada mata pelajaran {$this->mapel} tanggal {$tglFormatted}.\n\n"
            . "Mohon konfirmasi ke pihak sekolah atau wali kelas terkait ketidakhadiran ini. Terima kasih.";
    }
}

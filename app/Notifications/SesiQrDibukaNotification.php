<?php

namespace App\Notifications;

use App\Models\JadwalPelajaran;
use App\Models\SesiAbsensiQr;
use App\Services\Notification\Channels\TelegramChannel;
use App\Services\Notification\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SesiQrDibukaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public SesiAbsensiQr $sesiQr;
    public JadwalPelajaran $jadwal;

    public function __construct(SesiAbsensiQr $sesiQr, JadwalPelajaran $jadwal)
    {
        $this->sesiQr = $sesiQr;
        $this->jadwal = $jadwal;
    }

    public function via(object $notifiable): array
    {
        $settings = method_exists($notifiable, 'getOrCreateNotificationSetting')
            ? $notifiable->getOrCreateNotificationSetting()
            : null;

        if ($settings && ! $settings->notify_qr_sesi) {
            return [];
        }

        $channels = ['database'];

        if ($settings) {
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
        $mapel = $this->jadwal->mataPelajaran?->nama_pelajaran ?? 'Mata Pelajaran';
        $guru = $this->jadwal->guru?->nama_lengkap ?? 'Guru Pengampu';

        return [
            'type' => 'sesi_qr_dibuka',
            'title' => 'Presensi QR Dibuka: ' . $mapel,
            'message' => "Guru {$guru} telah membuka sesi presensi QR untuk kelas Anda. Silakan segera scan presensi di kelas.",
            'sesi_id' => $this->sesiQr->id,
            'jadwal_id' => $this->jadwal->id,
            'url' => route('siswa.absensi.scan'),
            'icon' => 'qrcode',
            'color' => 'indigo',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mapel = $this->jadwal->mataPelajaran?->nama_pelajaran ?? 'Mata Pelajaran';

        return (new MailMessage)
            ->subject("[SIAKAD SMA] Sesi Presensi QR Dibuka: {$mapel}")
            ->line("Sesi presensi QR untuk mata pelajaran {$mapel} sedang aktif.")
            ->action('Buka Kamera Scanner', route('siswa.absensi.scan'));
    }

    public function toTelegram(object $notifiable): string
    {
        $mapel = $this->jadwal->mataPelajaran?->nama_pelajaran ?? 'Mata Pelajaran';

        return "📱 *Presensi QR Dibuka*\n\n"
            . "Mata Pelajaran: *{$mapel}*\n"
            . "Sesi presensi aktif sekarang. Silakan scan QR code di kelas.\n"
            . "— _SIAKAD SMA_";
    }

    public function toWhatsApp(object $notifiable): string
    {
        $mapel = $this->jadwal->mataPelajaran?->nama_pelajaran ?? 'Mata Pelajaran';

        return "*[SIAKAD SMA] Presensi QR Dibuka*\n\n"
            . "Mata pelajaran *{$mapel}* telah membuka presensi QR. Buka menu scan di smartphone Anda untuk melakukan presensi.";
    }
}

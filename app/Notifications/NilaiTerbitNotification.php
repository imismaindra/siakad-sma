<?php

namespace App\Notifications;

use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Services\Notification\Channels\TelegramChannel;
use App\Services\Notification\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NilaiTerbitNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Siswa $siswa;
    public ?TahunAjaran $tahunAjaran;

    public function __construct(Siswa $siswa, ?TahunAjaran $tahunAjaran = null)
    {
        $this->siswa = $siswa;
        $this->tahunAjaran = $tahunAjaran;
    }

    public function via(object $notifiable): array
    {
        $settings = method_exists($notifiable, 'getOrCreateNotificationSetting')
            ? $notifiable->getOrCreateNotificationSetting()
            : null;

        if ($settings && ! $settings->notify_nilai) {
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
        $ta = $this->tahunAjaran ? "{$this->tahunAjaran->tahun} ({$this->tahunAjaran->semester})" : 'Semester Berjalan';

        return [
            'type' => 'nilai_terbit',
            'title' => 'Nilai & e-Rapor Telah Diterbitkan',
            'message' => "Nilai akademik dan e-Rapor siswa {$this->siswa->nama_lengkap} untuk periode {$ta} telah difinalisasi dan dapat diakses.",
            'siswa_id' => $this->siswa->id,
            'siswa_nama' => $this->siswa->nama_lengkap,
            'url' => $notifiable->isOrangTua() ? route('orang-tua.nilai') : route('siswa.nilai'),
            'icon' => 'academic-cap',
            'color' => 'emerald',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $ta = $this->tahunAjaran ? "{$this->tahunAjaran->tahun} ({$this->tahunAjaran->semester})" : 'Semester Berjalan';

        return (new MailMessage)
            ->subject("[SIAKAD SMA] e-Rapor dan Nilai Akademik Terbit: {$this->siswa->nama_lengkap}")
            ->greeting("Halo, {$notifiable->name}")
            ->line("Nilai hasil belajar dan e-Rapor resmi Kurikulum Merdeka untuk siswa **{$this->siswa->nama_lengkap}** periode **{$ta}** telah selesai difinalisasi.")
            ->line("Anda dapat mengunduh dokumen e-Rapor resmi dalam format PDF melalui portal SIAKAD SMA.")
            ->action('Lihat e-Rapor', $notifiable->isOrangTua() ? route('orang-tua.nilai') : route('siswa.nilai'))
            ->salutation("Salam Hangat,\nTim Kurikulum SIAKAD SMA");
    }

    public function toTelegram(object $notifiable): string
    {
        $ta = $this->tahunAjaran ? "{$this->tahunAjaran->tahun} ({$this->tahunAjaran->semester})" : 'Semester Berjalan';

        return "🎓 *Nilai & e-Rapor Telah Terbit*\n\n"
            . "Siswa: *{$this->siswa->nama_lengkap}*\n"
            . "Periode: *{$ta}*\n\n"
            . "Dokumen e-Rapor resmi telah difinalisasi dan siap diunduh di portal SIAKAD SMA.\n"
            . "— _SIAKAD SMA Terpadu_";
    }

    public function toWhatsApp(object $notifiable): string
    {
        $ta = $this->tahunAjaran ? "{$this->tahunAjaran->tahun} ({$this->tahunAjaran->semester})" : 'Semester Berjalan';

        return "*[SIAKAD SMA] Pengumuman e-Rapor*\n\n"
            . "Yth. {$notifiable->name},\n"
            . "Nilai akademik dan dokumen e-Rapor untuk *{$this->siswa->nama_lengkap}* periode {$ta} telah terbit. Silakan login ke portal untuk melihat atau mengunduh rapor.";
    }
}

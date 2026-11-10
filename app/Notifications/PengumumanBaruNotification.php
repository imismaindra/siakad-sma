<?php

namespace App\Notifications;

use App\Models\Pengumuman;
use App\Services\Notification\Channels\TelegramChannel;
use App\Services\Notification\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class PengumumanBaruNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Pengumuman $pengumuman;

    public function __construct(Pengumuman $pengumuman)
    {
        $this->pengumuman = $pengumuman;
    }

    public function via(object $notifiable): array
    {
        $settings = method_exists($notifiable, 'getOrCreateNotificationSetting')
            ? $notifiable->getOrCreateNotificationSetting()
            : null;

        if ($settings && ! $settings->notify_pengumuman) {
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
        $url = match (true) {
            $notifiable->isAdmin() => route('admin.pengumuman.index'),
            $notifiable->isGuru() => route('guru.pengumuman.show', $this->pengumuman),
            $notifiable->isSiswa() => route('siswa.pengumuman.show', $this->pengumuman),
            $notifiable->isOrangTua() => route('orang-tua.pengumuman.show', $this->pengumuman),
            default => url('/'),
        };

        return [
            'type' => 'pengumuman_baru',
            'title' => 'Pengumuman Baru: ' . $this->pengumuman->judul,
            'message' => Str::limit(strip_tags($this->pengumuman->konten), 120),
            'pengumuman_id' => $this->pengumuman->id,
            'target' => $this->pengumuman->target,
            'url' => $url,
            'icon' => 'speakerphone',
            'color' => 'blue',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = match (true) {
            $notifiable->isAdmin() => route('admin.pengumuman.index'),
            $notifiable->isGuru() => route('guru.pengumuman.show', $this->pengumuman),
            $notifiable->isSiswa() => route('siswa.pengumuman.show', $this->pengumuman),
            $notifiable->isOrangTua() => route('orang-tua.pengumuman.show', $this->pengumuman),
            default => url('/'),
        };

        return (new MailMessage)
            ->subject("[SIAKAD SMA] Pengumuman Baru: {$this->pengumuman->judul}")
            ->greeting("Halo, {$notifiable->name}")
            ->line("Terdapat pengumuman resmi terbaru dari sekolah:")
            ->line("**{$this->pengumuman->judul}**")
            ->line(Str::limit(strip_tags($this->pengumuman->konten), 250))
            ->action('Baca Selengkapnya', $url)
            ->salutation("Salam Hangat,\nSIAKAD SMA");
    }

    public function toTelegram(object $notifiable): string
    {
        return "📢 *Pengumuman Baru Sekolah*\n\n"
            . "*{$this->pengumuman->judul}*\n\n"
            . Str::limit(strip_tags($this->pengumuman->konten), 200) . "\n\n"
            . "— _SIAKAD SMA Terpadu_";
    }

    public function toWhatsApp(object $notifiable): string
    {
        return "*[SIAKAD SMA] Pengumuman Baru*\n\n"
            . "*{$this->pengumuman->judul}*\n\n"
            . Str::limit(strip_tags($this->pengumuman->konten), 200) . "\n\n"
            . "Silakan login ke portal SIAKAD SMA untuk informasi lengkap.";
    }
}

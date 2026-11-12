<?php

namespace App\Notifications;

use App\Models\Pengumuman;
use App\Services\Notification\Channels\TelegramChannel;
use App\Services\Notification\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengumumanBaruNotification extends Notification
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

        // Kirim email minimal jika pengumuman diset kirim_email dan user memiliki alamat email
        if ($this->pengumuman->kirim_email && ! empty($notifiable->email)) {
            if (! $settings || $settings->channel_mail) {
                $channels[] = 'mail';
            }
        }

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

        $mail = (new MailMessage)
            ->subject("[SIAKAD SMA] Pengumuman Baru: {$this->pengumuman->judul}")
            ->greeting("Halo, {$notifiable->name}")
            ->line("Terdapat pengumuman resmi terbaru dari sekolah:")
            ->line("**{$this->pengumuman->judul}**")
            ->line(Str::limit(strip_tags($this->pengumuman->konten), 300))
            ->action('Baca Selengkapnya di SIAKAD', $url)
            ->salutation("Salam Hangat,\nTim SIAKAD SMA");

        // Lampirkan dokumen ke email jika ada
        if ($this->pengumuman->dokumen && Storage::disk('public')->exists($this->pengumuman->dokumen)) {
            $mail->attach(Storage::disk('public')->path($this->pengumuman->dokumen), [
                'as' => $this->pengumuman->dokumen_nama ?? basename($this->pengumuman->dokumen),
            ]);
        }

        return $mail;
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

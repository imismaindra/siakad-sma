<?php

namespace App\Notifications;

use App\Services\Notification\Channels\TelegramChannel;
use App\Services\Notification\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        $settings = method_exists($notifiable, 'getOrCreateNotificationSetting')
            ? $notifiable->getOrCreateNotificationSetting()
            : null;

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
        return [
            'type' => 'test_notifikasi',
            'title' => 'Uji Coba Notifikasi SIAKAD SMA',
            'message' => 'Koneksi channel notifikasi Anda aktif dan berfungsi dengan baik.',
            'url' => route('notifications.settings'),
            'icon' => 'check-circle',
            'color' => 'emerald',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('[SIAKAD SMA] Uji Coba Pengiriman Notifikasi Email')
            ->greeting("Halo, {$notifiable->name}")
            ->line('Pesan ini merupakan uji coba untuk memastikan alamat email Anda dapat menerima pemberitahuan dari SIAKAD SMA.')
            ->line('Semua konfigurasi channel email Anda berjalan normal.')
            ->action('Buka Pengaturan Notifikasi', route('notifications.settings'))
            ->salutation("Salam Hangat,\nTim IT SIAKAD SMA");
    }

    public function toTelegram(object $notifiable): string
    {
        return "✅ *Uji Coba Notifikasi Berhasil*\n\n"
            . "Halo *{$notifiable->name}*, Bot Telegram SIAKAD SMA berhasil terhubung ke akun Anda!\n"
            . "— _SIAKAD SMA Terpadu_";
    }

    public function toWhatsApp(object $notifiable): string
    {
        return "*[SIAKAD SMA] Uji Coba Notifikasi WhatsApp*\n\n"
            . "Halo {$notifiable->name}, nomor WhatsApp Anda berhasil terhubung dengan sistem notifikasi SIAKAD SMA.";
    }
}

<?php

namespace App\Services\Notification\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramChannel
{
    /**
     * Kirim notifikasi melalui Telegram Bot API.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toTelegram')) {
            return;
        }

        $chatId = $notifiable->routeNotificationFor('telegram', $notification);

        if (empty($chatId)) {
            return;
        }

        $message = $notification->toTelegram($notifiable);
        $token = config('notification.telegram.bot_token');

        if (empty($token)) {
            Log::info("[TelegramChannel-Mock] Notifikasi ke Chat ID {$chatId}:\n{$message}");
            return;
        }

        try {
            $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);

            if (! $response->successful()) {
                Log::error("[TelegramChannel] Gagal kirim pesan ke {$chatId}: " . $response->body());
            }
        } catch (\Throwable $e) {
            Log::error("[TelegramChannel] Exception kirim ke {$chatId}: " . $e->getMessage());
        }
    }
}

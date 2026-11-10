<?php

namespace App\Services\Notification\Channels;

use App\Services\Notification\WhatsAppService;
use Illuminate\Notifications\Notification;

class WhatsAppChannel
{
    protected WhatsAppService $service;

    public function __construct(WhatsAppService $service)
    {
        $this->service = $service;
    }

    /**
     * Kirim notifikasi melalui WhatsApp Adapter.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $phoneNumber = $notifiable->routeNotificationFor('whatsapp', $notification);

        if (empty($phoneNumber)) {
            return;
        }

        $message = $notification->toWhatsApp($notifiable);
        $this->service->send($phoneNumber, $message);
    }
}

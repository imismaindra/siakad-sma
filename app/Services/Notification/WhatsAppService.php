<?php

namespace App\Services\Notification;

use App\Services\Notification\Adapters\FonnteWhatsAppAdapter;
use App\Services\Notification\Adapters\LogWhatsAppAdapter;
use App\Services\Notification\Contracts\WhatsAppAdapterInterface;

class WhatsAppService
{
    protected WhatsAppAdapterInterface $adapter;

    public function __construct(?WhatsAppAdapterInterface $adapter = null)
    {
        if ($adapter) {
            $this->adapter = $adapter;
            return;
        }

        $adapterName = config('notification.whatsapp.adapter', 'log');

        $this->adapter = match ($adapterName) {
            'fonnte' => new FonnteWhatsAppAdapter(),
            default => new LogWhatsAppAdapter(),
        };
    }

    public function send(string $phoneNumber, string $message): bool
    {
        if (empty(trim($phoneNumber))) {
            return false;
        }

        return $this->adapter->send($phoneNumber, $message);
    }

    public function getAdapterName(): string
    {
        return $this->adapter->name();
    }

    public function adapterName(): string
    {
        return $this->getAdapterName();
    }

    public function adapter(): WhatsAppAdapterInterface
    {
        return $this->adapter;
    }
}

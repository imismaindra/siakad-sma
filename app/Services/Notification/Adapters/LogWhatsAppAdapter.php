<?php

namespace App\Services\Notification\Adapters;

use App\Services\Notification\Contracts\WhatsAppAdapterInterface;
use Illuminate\Support\Facades\Log;

class LogWhatsAppAdapter implements WhatsAppAdapterInterface
{
    public function send(string $phoneNumber, string $message): bool
    {
        Log::info("[WhatsApp-Log-Adapter] Mengirim pesan ke {$phoneNumber}:\n{$message}");
        return true;
    }

    public function name(): string
    {
        return 'log';
    }
}

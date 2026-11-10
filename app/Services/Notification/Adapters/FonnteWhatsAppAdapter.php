<?php

namespace App\Services\Notification\Adapters;

use App\Services\Notification\Contracts\WhatsAppAdapterInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteWhatsAppAdapter implements WhatsAppAdapterInterface
{
    protected string $token;
    protected string $endpoint;

    public function __construct(?string $token = null, ?string $endpoint = null)
    {
        $this->token = $token ?? config('notification.whatsapp.fonnte.token', '');
        $this->endpoint = $endpoint ?? config('notification.whatsapp.fonnte.endpoint', 'https://api.fonnte.com/send');
    }

    public function send(string $phoneNumber, string $message): bool
    {
        if (empty($this->token)) {
            Log::warning("[FonnteWhatsAppAdapter] Token tidak dikonfigurasi, pesan dialihkan ke log: {$message}");
            return true;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->asForm()->post($this->endpoint, [
                'target' => $phoneNumber,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error("[FonnteWhatsAppAdapter] Gagal mengirim pesan ke {$phoneNumber}: " . $response->body());
            return false;
        } catch (\Throwable $e) {
            Log::error("[FonnteWhatsAppAdapter] Exception saat kirim ke {$phoneNumber}: " . $e->getMessage());
            return false;
        }
    }

    public function name(): string
    {
        return 'fonnte';
    }
}

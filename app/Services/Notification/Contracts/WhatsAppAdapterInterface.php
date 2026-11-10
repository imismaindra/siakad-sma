<?php

namespace App\Services\Notification\Contracts;

interface WhatsAppAdapterInterface
{
    /**
     * Kirim pesan teks WhatsApp ke nomor tujuan.
     *
     * @param string $phoneNumber Nomor tujuan (format: 08xx atau 628xx)
     * @param string $message Isi pesan
     * @return bool Status pengiriman
     */
    public function send(string $phoneNumber, string $message): bool;

    /**
     * Nama identitas adapter.
     */
    public function name(): string;
}

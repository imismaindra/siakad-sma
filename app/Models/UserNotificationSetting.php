<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotificationSetting extends Model
{
    protected $table = 'user_notification_settings';

    protected $fillable = [
        'user_id',
        'telegram_chat_id',
        'whatsapp_number',
        'channel_in_app',
        'channel_mail',
        'channel_telegram',
        'channel_whatsapp',
        'notify_alpa',
        'notify_nilai',
        'notify_pengumuman',
        'notify_qr_sesi',
    ];

    protected function casts(): array
    {
        return [
            'channel_in_app' => 'boolean',
            'channel_mail' => 'boolean',
            'channel_telegram' => 'boolean',
            'channel_whatsapp' => 'boolean',
            'notify_alpa' => 'boolean',
            'notify_nilai' => 'boolean',
            'notify_pengumuman' => 'boolean',
            'notify_qr_sesi' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

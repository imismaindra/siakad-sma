<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->unique();
            $table->string('telegram_chat_id', 50)->nullable();
            $table->string('whatsapp_number', 30)->nullable();
            
            // Channel Preferences (Active channels for this user)
            $table->boolean('channel_in_app')->default(true);
            $table->boolean('channel_mail')->default(true);
            $table->boolean('channel_telegram')->default(false);
            $table->boolean('channel_whatsapp')->default(false);
            
            // Topic Preferences (Events this user wants to receive)
            $table->boolean('notify_alpa')->default(true);
            $table->boolean('notify_nilai')->default(true);
            $table->boolean('notify_pengumuman')->default(true);
            $table->boolean('notify_qr_sesi')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notification_settings');
    }
};

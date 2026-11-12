<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengumumans', function (Blueprint $table) {
            $table->string('gambar')->nullable()->after('konten');
            $table->string('dokumen')->nullable()->after('gambar');
            $table->string('dokumen_nama')->nullable()->after('dokumen');
            $table->boolean('kirim_email')->default(true)->after('is_aktif');
        });
    }

    public function down(): void
    {
        Schema::table('pengumumans', function (Blueprint $table) {
            $table->dropColumn([
                'gambar',
                'dokumen',
                'dokumen_nama',
                'kirim_email',
            ]);
        });
    }
};

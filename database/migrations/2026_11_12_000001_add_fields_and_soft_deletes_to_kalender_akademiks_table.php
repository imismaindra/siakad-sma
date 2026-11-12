<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kalender_akademiks', function (Blueprint $table) {
            $table->string('dokumen')->nullable()->after('deskripsi');
            $table->string('dokumen_nama')->nullable()->after('dokumen');
            $table->time('jam_mulai')->nullable()->after('tanggal_selesai');
            $table->time('jam_selesai')->nullable()->after('jam_mulai');
            $table->boolean('lewati_akhir_pekan')->default(false)->after('is_libur');
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('kalender_akademiks', function (Blueprint $table) {
            $table->dropColumn([
                'dokumen',
                'dokumen_nama',
                'jam_mulai',
                'jam_selesai',
                'lewati_akhir_pekan',
            ]);
            $table->dropSoftDeletes();
        });
    }
};

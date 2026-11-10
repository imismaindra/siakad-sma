<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kalender_akademiks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->nullable()->constrained('tahun_ajarans')->nullOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('kategori', 30)->default('kegiatan'); // libur, ujian, rapor, kegiatan, lainnya
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('warna', 20)->default('blue'); // blue, emerald, amber, rose, purple, indigo
            $table->string('target', 30)->default('semua'); // semua, guru, siswa, orang_tua
            $table->boolean('is_libur')->default(false);
            $table->boolean('is_aktif')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['tanggal_mulai', 'tanggal_selesai']);
            $table->index(['tahun_ajaran_id', 'is_aktif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kalender_akademiks');
    }
};

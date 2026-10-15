<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel Sesi Absensi QR Dinamis (F1)
        Schema::create('sesi_absensi_qrs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_pelajaran_id')->constrained('jadwal_pelajarans')->cascadeOnDelete();
            $table->foreignId('absensi_id')->nullable()->constrained('absensis')->nullOnDelete();
            $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
            $table->date('tanggal');
            $table->timestamp('dibuka_pada')->useCurrent();
            $table->timestamp('ditutup_pada')->nullable();
            $table->string('secret', 64);
            $table->unsignedSmallInteger('durasi_token_detik')->default(30);
            $table->enum('status', ['aktif', 'ditutup'])->default('aktif');
            $table->timestamps();

            $table->index(['jadwal_pelajaran_id', 'tanggal', 'status']);
        });

        // 2. Tambah kolom metode di tabel absensis
        Schema::table('absensis', function (Blueprint $table) {
            $table->enum('metode', ['manual', 'qr'])->default('manual')->after('tanggal');
        });

        // 3. Tambah kolom metode & validasi di tabel detail_absensis
        Schema::table('detail_absensis', function (Blueprint $table) {
            $table->enum('metode', ['manual', 'qr', 'override'])->default('manual')->after('status');
            $table->timestamp('waktu_absen')->nullable()->after('metode');
            $table->double('jarak_meter')->nullable()->after('waktu_absen');
            $table->double('akurasi_meter')->nullable()->after('jarak_meter');
            $table->boolean('lokasi_valid')->default(true)->after('akurasi_meter');
            $table->string('alasan_override')->nullable()->after('lokasi_valid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_absensis', function (Blueprint $table) {
            $table->dropColumn([
                'metode',
                'waktu_absen',
                'jarak_meter',
                'akurasi_meter',
                'lokasi_valid',
                'alasan_override',
            ]);
        });

        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn('metode');
        });

        Schema::dropIfExists('sesi_absensi_qrs');
    }
};

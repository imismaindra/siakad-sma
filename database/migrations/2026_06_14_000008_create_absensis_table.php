<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_pelajaran_id')->constrained('jadwal_pelajarans')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('materi')->nullable()->comment('Materi yang diajarkan');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['jadwal_pelajaran_id', 'tanggal'], 'unique_absensi_jadwal_tanggal');
        });

        Schema::create('detail_absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('absensi_id')->constrained('absensis')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa'])->default('hadir');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['absensi_id', 'siswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_absensis');
        Schema::dropIfExists('absensis');
    }
};

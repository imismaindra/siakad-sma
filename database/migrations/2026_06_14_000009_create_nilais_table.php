<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Konfigurasi bobot nilai per mata pelajaran per tahun ajaran
        Schema::create('bobot_nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
            $table->decimal('bobot_harian', 5, 2)->default(40)->comment('Persen bobot nilai harian/tugas');
            $table->decimal('bobot_uts', 5, 2)->default(30)->comment('Persen bobot UTS');
            $table->decimal('bobot_uas', 5, 2)->default(30)->comment('Persen bobot UAS');
            $table->timestamps();

            $table->unique(['mata_pelajaran_id', 'tahun_ajaran_id', 'kelas_id'], 'unique_bobot_nilai');
        });

        // Nilai per siswa per mata pelajaran per semester
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
            $table->decimal('rata_rata_harian', 6, 2)->nullable();
            $table->decimal('nilai_uts', 6, 2)->nullable();
            $table->decimal('nilai_uas', 6, 2)->nullable();
            $table->decimal('nilai_akhir', 6, 2)->nullable()->comment('Dihitung otomatis dari bobot');
            $table->text('deskripsi')->nullable()->comment('Catatan/deskripsi guru per siswa');
            $table->boolean('is_final')->default(false)->comment('Apakah nilai sudah dikunci');
            $table->timestamps();

            $table->unique(['siswa_id', 'mata_pelajaran_id', 'tahun_ajaran_id'], 'unique_nilai_siswa_mapel');
        });

        // Detail nilai harian (tugas, kuis, ulangan harian)
        Schema::create('detail_nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nilai_id')->constrained('nilais')->cascadeOnDelete();
            $table->string('judul')->comment('Nama tugas/ulangan/kuis');
            $table->enum('jenis', ['tugas', 'kuis', 'ulangan_harian', 'praktik', 'lainnya'])->default('tugas');
            $table->decimal('nilai', 6, 2);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_nilais');
        Schema::dropIfExists('nilais');
        Schema::dropIfExists('bobot_nilais');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurusans', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // IPA, IPS, Bahasa, dll
            $table->string('kode', 10)->unique(); // IPA, IPS, BHS
            $table->timestamps();
        });

        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusans')->nullOnDelete();
            $table->foreignId('wali_kelas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('tingkat', ['X', 'XI', 'XII']);
            $table->integer('nomor'); // 1, 2, 3, dst
            $table->string('nama')->nullable(); // Will be set via model
            $table->integer('kapasitas')->default(36);
            $table->timestamps();

            $table->unique(['tahun_ajaran_id', 'jurusan_id', 'tingkat', 'nomor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('jurusans');
    }
};

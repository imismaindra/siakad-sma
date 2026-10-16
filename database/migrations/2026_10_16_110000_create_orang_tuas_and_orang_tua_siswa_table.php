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
        Schema::create('orang_tuas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('nama_lengkap', 150);
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('no_telepon', 20)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });

        Schema::create('orang_tua_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orang_tua_id')->constrained('orang_tuas')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->enum('hubungan', ['ayah', 'ibu', 'wali'])->default('wali');
            $table->timestamps();

            $table->unique(['orang_tua_id', 'siswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orang_tua_siswa');
        Schema::dropIfExists('orang_tuas');
    }
};

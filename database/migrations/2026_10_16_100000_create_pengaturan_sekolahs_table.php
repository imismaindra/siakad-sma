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
        Schema::create('pengaturan_sekolahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah')->default('SMA Negeri 1');
            $table->string('alamat')->nullable()->default('Jl. Pendidikan No. 1, Kota Bandung');
            $table->decimal('latitude', 10, 7)->default(-6.9147440);
            $table->decimal('longitude', 10, 7)->default(107.6098100);
            $table->unsignedInteger('radius_meter')->default(100);
            $table->unsignedInteger('max_accuracy_meter')->default(80);
            $table->boolean('is_geofencing_active')->default(true);
            $table->boolean('enforce_anti_mock')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sekolahs');
    }
};

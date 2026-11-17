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
        Schema::create('sekolah_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah', 150);
            $table->string('npsn', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('telepon', 25)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('website', 100)->nullable();
            $table->string('kepala_sekolah', 100)->nullable();
            $table->string('nip_kepala_sekolah', 30)->nullable();
            $table->string('logo')->nullable();
            $table->time('jam_masuk_sekolah')->default('07:00:00');
            $table->unsignedSmallInteger('toleransi_terlambat_menit')->default(10);
            $table->boolean('geofencing_aktif')->default(false);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('radius_meter')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolah_settings');
    }
};

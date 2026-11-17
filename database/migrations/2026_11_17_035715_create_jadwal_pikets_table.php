<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_pikets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
            $table->foreignId('petugas_id')->constrained('users')->cascadeOnDelete();
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu']);
            $table->boolean('is_koordinator')->default(false);
            $table->string('keterangan', 150)->nullable();
            $table->timestamps();

            // Cegah petugas piket yang sama terdaftar ganda di hari & tahun ajaran yang sama
            $table->unique(['tahun_ajaran_id', 'petugas_id', 'hari'], 'jadwal_piket_petugas_hari_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pikets');
    }
};
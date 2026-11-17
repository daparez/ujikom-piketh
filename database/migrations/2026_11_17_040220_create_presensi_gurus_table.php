<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi_gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_pelajaran_id')->constrained('jadwal_pelajarans')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'terlambat', 'izin', 'sakit', 'tanpa_keterangan'])->default('hadir');
            $table->time('jam_masuk')->nullable();
            $table->string('keterangan', 200)->nullable();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['jadwal_pelajaran_id', 'tanggal'], 'presensi_jadwal_tanggal_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_gurus');
    }
};
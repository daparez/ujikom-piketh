<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_pelajarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->restrictOnDelete();
            $table->foreignId('guru_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('jam_pelajaran_id')->constrained('jam_pelajarans')->restrictOnDelete();
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu']);
            $table->string('ruangan', 30)->nullable();
            $table->timestamps();

            // Cegah bentrok kelas di slot waktu yang sama pada tahun ajaran yang sama
            $table->unique(['tahun_ajaran_id', 'kelas_id', 'hari', 'jam_pelajaran_id'], 'jadwal_kelas_slot_unique');

            // Cegah bentrok guru di slot waktu yang sama pada tahun ajaran yang sama
            $table->unique(['tahun_ajaran_id', 'guru_id', 'hari', 'jam_pelajaran_id'], 'jadwal_guru_slot_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pelajarans');
    }
};
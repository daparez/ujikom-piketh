<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas_kosongs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_pelajaran_id')->constrained('jadwal_pelajarans')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('alasan', ['izin', 'sakit', 'dinas_luar', 'tanpa_keterangan'])->default('tanpa_keterangan');
            $table->foreignId('guru_pengganti_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['kosong', 'tertangani', 'tugas_mandiri'])->default('kosong');
            $table->text('catatan_tugas')->nullable();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['jadwal_pelajaran_id', 'tanggal'], 'kelas_kosong_jadwal_tanggal_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas_kosongs');
    }
};
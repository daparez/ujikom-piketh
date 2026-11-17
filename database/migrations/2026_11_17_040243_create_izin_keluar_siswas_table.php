<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('izin_keluar_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('jenis', ['izin_sementara', 'pulang_awal'])->default('izin_sementara');
            $table->time('jam_keluar');
            $table->time('jam_kembali')->nullable();
            $table->string('alasan', 200);
            $table->string('penjemput', 100)->nullable();
            $table->enum('status', ['keluar', 'kembali', 'pulang'])->default('keluar');
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('izin_keluar_siswas');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_pelanggarans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique(); // Contoh: P-001
            $table->string('nama', 150);          // Contoh: Terlambat masuk, atribut tidak lengkap
            $table->enum('kategori', ['ringan', 'sedang', 'berat'])->default('ringan');
            $table->unsignedSmallInteger('poin'); // Bobot poin pelanggaran
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_pelanggarans');
    }
};
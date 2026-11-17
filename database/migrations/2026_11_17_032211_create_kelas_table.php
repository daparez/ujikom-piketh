<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
            $table->string('nama', 30); // Contoh: X IPA 1 / XII RPL 1
            $table->unsignedTinyInteger('tingkat'); // 10, 11, 12
            $table->foreignId('wali_kelas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tahun_ajaran_id', 'nama', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
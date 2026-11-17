<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggaran_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('jenis_pelanggaran_id')->constrained('jenis_pelanggarans')->restrictOnDelete();
            $table->date('tanggal');
            $table->unsignedSmallInteger('poin');
            $table->text('kronologi')->nullable();
            $table->string('tindak_lanjut', 150)->nullable();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggaran_siswas');
    }
};
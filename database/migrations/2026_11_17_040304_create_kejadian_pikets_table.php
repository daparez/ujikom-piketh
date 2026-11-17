<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kejadian_pikets', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('judul', 150);
            $table->enum('kategori', ['akademik', 'kedisiplinan', 'sarpras', 'keamanan', 'lainnya'])->default('lainnya');
            $table->text('uraian');
            $table->text('tindakan')->nullable();
            $table->enum('status', ['terbuka', 'ditangani', 'selesai'])->default('selesai');
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kejadian_pikets');
    }
};
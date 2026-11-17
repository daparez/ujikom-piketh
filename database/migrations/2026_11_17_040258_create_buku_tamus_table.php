<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_tamus', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama_tamu', 100);
            $table->string('instansi', 100)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('bertemu_dengan', 100);
            $table->string('keperluan', 200);
            $table->time('jam_masuk');
            $table->time('jam_keluar')->nullable();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_tamus');
    }
};
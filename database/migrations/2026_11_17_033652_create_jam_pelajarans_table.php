<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jam_pelajarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('jam_ke')->unique(); // Urutan jam: 1, 2, 3, ...
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->boolean('is_istirahat')->default(false); // Tandai slot istirahat (tidak dihitung kelas kosong)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jam_pelajarans');
    }
};
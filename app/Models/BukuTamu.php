<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BukuTamu extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'nama_tamu',
        'instansi',
        'no_hp',
        'bertemu_dengan',
        'keperluan',
        'jam_masuk',
        'jam_keluar',
        'petugas_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
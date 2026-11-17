<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KejadianPiket extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'waktu',
        'judul',
        'kategori',
        'uraian',
        'tindakan',
        'status',
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
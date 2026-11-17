<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KelasKosong extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_pelajaran_id',
        'tanggal',
        'alasan',
        'guru_pengganti_id',
        'status',
        'catatan_tugas',
        'petugas_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function jadwalPelajaran(): BelongsTo
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }

    public function guruPengganti(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_pengganti_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
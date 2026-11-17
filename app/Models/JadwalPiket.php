<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPiket extends Model
{
    use HasFactory;

    public const HARI_LIST = [
        'senin'  => 'Senin',
        'selasa' => 'Selasa',
        'rabu'   => 'Rabu',
        'kamis'  => 'Kamis',
        'jumat'  => 'Jumat',
        'sabtu'  => 'Sabtu',
    ];

    protected $fillable = [
        'tahun_ajaran_id',
        'petugas_id',
        'hari',
        'is_koordinator',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'is_koordinator' => 'boolean',
        ];
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['tahun_ajaran_id'] ?? null, fn (Builder $q, $id) => $q->where('tahun_ajaran_id', $id));
        $query->when($filters['hari'] ?? null, fn (Builder $q, string $hari) => $q->where('hari', $hari));
        $query->when($filters['petugas_id'] ?? null, fn (Builder $q, $id) => $q->where('petugas_id', $id));
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanIzin extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis',
        'alasan',
        'lampiran',
        'pesan_tugas',
        'status',
        'catatan_admin',
        'diproses_oleh',
        'diproses_pada',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'diproses_pada' => 'datetime',
        ];
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function pemroses(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function (Builder $q, string $search) {
            $q->whereHas('guru', fn (Builder $g) => $g->where('name', 'like', "%{$search}%"));
        });

        $query->when($filters['status'] ?? null, fn (Builder $q, string $status) => $q->where('status', $status));
        $query->when($filters['jenis'] ?? null, fn (Builder $q, string $jenis) => $q->where('jenis', $jenis));
    }
}
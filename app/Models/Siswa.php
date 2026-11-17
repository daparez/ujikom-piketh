<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kelas_id',
        'nis',
        'nisn',
        'nama',
        'jenis_kelamin',
        'no_hp_ortu',
        'status',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function (Builder $q, string $search) {
            $q->where(function (Builder $sub) use ($search) {
                $sub->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        });

        $query->when($filters['kelas_id'] ?? null, fn (Builder $q, $kelasId) => $q->where('kelas_id', $kelasId));
        $query->when($filters['status'] ?? null, fn (Builder $q, string $status) => $q->where('status', $status));
    }
}
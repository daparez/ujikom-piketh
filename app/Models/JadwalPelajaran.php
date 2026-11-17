<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPelajaran extends Model
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
        'kelas_id',
        'mata_pelajaran_id',
        'guru_id',
        'jam_pelajaran_id',
        'hari',
        'ruangan',
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function jamPelajaran(): BelongsTo
    {
        return $this->belongsTo(JamPelajaran::class, 'jam_pelajaran_id');
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['tahun_ajaran_id'] ?? null, fn (Builder $q, $id) => $q->where('tahun_ajaran_id', $id));
        $query->when($filters['kelas_id'] ?? null, fn (Builder $q, $id) => $q->where('kelas_id', $id));
        $query->when($filters['guru_id'] ?? null, fn (Builder $q, $id) => $q->where('guru_id', $id));
        $query->when($filters['hari'] ?? null, fn (Builder $q, string $hari) => $q->where('hari', $hari));
    }
}
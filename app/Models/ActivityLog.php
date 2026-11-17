<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'modul',
        'aksi',
        'deskripsi',
        'ip_address',
        'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function catat(string $modul, string $aksi, string $deskripsi): self
    {
        return self::create([
            'user_id'    => Auth::id(),
            'modul'      => $modul,
            'aksi'       => strtolower($aksi),
            'deskripsi'  => $deskripsi,
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 255),
        ]);
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function (Builder $q, string $search) {
            $q->where(function (Builder $sub) use ($search) {
                $sub->where('deskripsi', 'like', "%{$search}%")
                    ->orWhere('modul', 'like', "%{$search}%")
                    ->orWhereHas('user', fn (Builder $u) => $u->where('name', 'like', "%{$search}%"));
            });
        });

        $query->when($filters['modul'] ?? null, fn (Builder $q, string $modul) => $q->where('modul', $modul));
        $query->when($filters['aksi'] ?? null, fn (Builder $q, string $aksi) => $q->where('aksi', $aksi));
        $query->when($filters['tanggal'] ?? null, fn (Builder $q, string $tanggal) => $q->whereDate('created_at', $tanggal));
    }
}
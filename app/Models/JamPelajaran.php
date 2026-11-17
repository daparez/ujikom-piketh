<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamPelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'jam_ke',
        'jam_mulai',
        'jam_selesai',
        'is_istirahat',
    ];

    protected function casts(): array
    {
        return [
            'jam_ke' => 'integer',
            'is_istirahat' => 'boolean',
        ];
    }

    public function scopePelajaran(Builder $query): Builder
    {
        return $query->where('is_istirahat', false);
    }
}
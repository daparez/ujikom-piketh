<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SekolahSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_sekolah',
        'npsn',
        'alamat',
        'telepon',
        'email',
        'website',
        'kepala_sekolah',
        'nip_kepala_sekolah',
        'logo',
        'jam_masuk_sekolah',
        'toleransi_terlambat_menit',
        'geofencing_aktif',
        'latitude',
        'longitude',
        'radius_meter',
    ];

    protected function casts(): array
    {
        return [
            'toleransi_terlambat_menit' => 'integer',
            'geofencing_aktif' => 'boolean',
            'latitude' => 'float',
            'longitude' => 'float',
            'radius_meter' => 'integer',
        ];
    }
}
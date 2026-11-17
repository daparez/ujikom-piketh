<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Petugas = 'petugas';
    case Guru = 'guru';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Petugas => 'Petugas Piket',
            self::Guru => 'Guru Pengajar',
        };
    }
}
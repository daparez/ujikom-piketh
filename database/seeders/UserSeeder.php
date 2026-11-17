<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // 1. Akun Admin
            [
                'name'      => 'Administrator Piket',
                'email'     => 'admin@gmail.com',
                'password'  => Hash::make('password'),
                'role'      => Role::Admin,
                'nip'       => '198501012010011001',
                'no_hp'     => '081234567801',
                'is_active' => true,
            ],

            // 2. Akun Petugas Piket
            [
                'name'      => 'Budi Santoso, S.Pd.',
                'email'     => 'petugas@gmail.com',
                'password'  => Hash::make('password'),
                'role'      => Role::Petugas,
                'nip'       => '198804122014021002',
                'no_hp'     => '081234567802',
                'is_active' => true,
            ],

            // 3. Akun Guru Pengajar / Wali Kelas
            [
                'name'      => 'Siti Aminah, M.Pd.',
                'email'     => 'guru@gmail.com',
                'password'  => Hash::make('password'),
                'role'      => Role::Guru,
                'nip'       => '199007202015032003',
                'no_hp'     => '081234567803',
                'is_active' => true,
            ],
            [
                'name'      => 'Ahmad Fauzi, S.Kom.',
                'email'     => 'ahmad.fauzi@sekolah.sch.id',
                'password'  => Hash::make('password'),
                'role'      => Role::Guru,
                'nip'       => '199211052019031004',
                'no_hp'     => '081234567804',
                'is_active' => true,
            ],
            [
                'name'      => 'Rina Marlina, S.Si.',
                'email'     => 'rina.marlina@sekolah.sch.id',
                'password'  => Hash::make('password'),
                'role'      => Role::Guru,
                'nip'       => '199403182020122005',
                'no_hp'     => '081234567805',
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                array_merge($user, ['email_verified_at' => now()])
            );
        }
    }
}
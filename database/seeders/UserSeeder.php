<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Administrator',
                'email'    => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'role'     => User::ROLE_ADMIN,
            ],
            [
                'name'     => 'Pimpinan',
                'email'    => 'pimpinan@gmail.com',
                'password' => Hash::make('pimpinan123'),
                'role'     => User::ROLE_PIMPINAN,
            ],
            [
                'name'     => 'Kepala Gudang',
                'email'    => 'kepala@gmail.com',
                'password' => Hash::make('kepala123'),
                'role'     => User::ROLE_KEPALA_GUDANG,
            ],
            [
                'name'     => 'Budi Santoso',
                'email'    => 'karyawan@gmail.com',
                'password' => Hash::make('karyawan123'),
                'role'     => User::ROLE_KARYAWAN,
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'is_active'         => true,
                    'email_verified_at' => now(),
                ])
            );
        }

        $this->command->info('✅ Users berhasil dibuat:');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Administrator', 'admin@gmail.com',    'admin123'],
                ['Pimpinan',      'pimpinan@gmail.com', 'pimpinan123'],
                ['Kepala Gudang', 'kepala@gmail.com',   'kepala123'],
                ['Pegawai',       'karyawan@gmail.com', 'karyawan123'],
            ]
        );
    }
}
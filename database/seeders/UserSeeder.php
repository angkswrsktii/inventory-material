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
            ['name' => 'Administrator', 'email' => 'admin@mail.com', 'role' => User::ROLE_ADMIN],
            ['name' => 'Pimpinan', 'email' => 'pimpinan@mail.com', 'role' => User::ROLE_PIMPINAN],
            ['name' => 'Kepala Gudang', 'email' => 'gudang@mail.com', 'role' => User::ROLE_KEPALA_GUDANG],
            ['name' => 'Karyawan', 'email' => 'karyawan@mail.com', 'role' => User::ROLE_KARYAWAN],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name'     => $user['name'],
                    'password' => Hash::make('password'),
                    'role'     => $user['role'],
                ]
            );
        }
    }
}
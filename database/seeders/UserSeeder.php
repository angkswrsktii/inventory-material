<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@rawmatpro.com'],
            [
                'name'      => 'Administrator',
                'email'     => 'admin@rawmatpro.com',
                'password'  => Hash::make('admin123'),
                'role'      => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Karyawan 1
        User::updateOrCreate(
            ['email' => 'karyawan@rawmatpro.com'],
            [
                'name'      => 'Budi Santoso',
                'email'     => 'karyawan@rawmatpro.com',
                'password'  => Hash::make('karyawan123'),
                'role'      => 'karyawan',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Users berhasil dibuat:');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',    'admin@rawmatpro.com',    'admin123'],
                ['Karyawan', 'karyawan@rawmatpro.com', 'karyawan123'],
            ]
        );
    }
}

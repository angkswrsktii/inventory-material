<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('suppliers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('suppliers')->insert([
            ['name' => 'BME',      'contact_person' => null, 'phone' => null, 'email' => null, 'address' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'GDM',      'contact_person' => null, 'phone' => null, 'email' => null, 'address' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ISTW',     'contact_person' => null, 'phone' => null, 'email' => null, 'address' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MPP',      'contact_person' => null, 'phone' => null, 'email' => null, 'address' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MSD',      'contact_person' => null, 'phone' => null, 'email' => null, 'address' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MZ',       'contact_person' => null, 'phone' => null, 'email' => null, 'address' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SGS',      'contact_person' => null, 'phone' => null, 'email' => null, 'address' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SPINDO',   'contact_person' => null, 'phone' => null, 'email' => null, 'address' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SPS',      'contact_person' => null, 'phone' => null, 'email' => null, 'address' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SSI',      'contact_person' => null, 'phone' => null, 'email' => null, 'address' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'TOP TUBE', 'contact_person' => null, 'phone' => null, 'email' => null, 'address' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
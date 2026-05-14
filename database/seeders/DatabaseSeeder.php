<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SupplierSeeder::class,
            CustomerSeeder::class,
            WarehouseSeeder::class,
            MaterialSeeder::class,
            PartSeeder::class,
            StockSeeder::class,
        ]);
    }
}

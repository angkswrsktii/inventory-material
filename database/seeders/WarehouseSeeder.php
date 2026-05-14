<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        Warehouse::create([
            'code' => 'WH01',
            'name' => 'Gudang Utama (Bahan Baku)',
            'location' => 'Blok A',
        ]);
        
        Warehouse::create([
            'code' => 'WH02',
            'name' => 'Gudang Barang Jadi',
            'location' => 'Blok B',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Part;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $warehouse1 = Warehouse::where('code', 'WH01')->first();
        $warehouse2 = Warehouse::where('code', 'WH02')->first();
        
        $material1 = Material::where('code', 'MAT-SPCC-001')->first();
        $part1 = Part::where('part_no', 'P-001-A')->first();
        
        if ($warehouse1 && $material1) {
            Stock::create([
                'm_warehouse_id' => $warehouse1->id,
                'm_material_id' => $material1->id,
                'minimum_stock' => 10,
                'max_stock' => 100,
                'current_stock' => 50,
            ]);
        }
        
        if ($warehouse2 && $part1) {
            Stock::create([
                'm_warehouse_id' => $warehouse2->id,
                'm_part_id' => $part1->id,
                'minimum_stock' => 5,
                'max_stock' => 50,
                'current_stock' => 20,
            ]);
        }
    }
}

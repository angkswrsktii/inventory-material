<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $supplier1 = Supplier::where('code', 'SUP001')->first();
        
        Material::create([
            'm_supplier_id' => $supplier1?->id,
            'code' => 'MAT-SPCC-001',
            'name' => 'SPCC SD',
            'specification' => '1.2 x 1219 x 2438',
            'unit' => 'Lembar',
            'panjang_material' => 2438,
            'description' => 'Plat Baja',
        ]);
        
        Material::create([
            'm_supplier_id' => $supplier1?->id,
            'code' => 'MAT-SUS-001',
            'name' => 'SUS 304',
            'specification' => '2.0 x 1000 x 2000',
            'unit' => 'Lembar',
            'panjang_material' => 2000,
            'description' => 'Stainless Steel',
        ]);
    }
}
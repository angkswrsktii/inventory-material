<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Part;
use Illuminate\Database\Seeder;

class PartSeeder extends Seeder
{
    public function run(): void
    {
        $customer1 = Customer::where('code', 'CUS001')->first();
        
        Part::create([
            'm_customer_id' => $customer1?->id,
            'part_no' => 'P-001-A',
            'part_name' => 'Bracket Engine Right',
            'panjang_part' => 150.5,
            'bq' => 0.05,
            'description' => 'Project A - Bracket',
        ]);
    }
}

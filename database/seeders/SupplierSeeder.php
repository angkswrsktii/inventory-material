<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::create([
            'code' => 'SUP001',
            'name' => 'PT. Baja Nusantara',
            'contact_person' => 'Budi Santoso',
            'phone' => '081234567890',
            'email' => 'info@bajanusantara.com',
            'address' => 'Jl. Industri No. 1, Jakarta',
        ]);
        
        Supplier::create([
            'code' => 'SUP002',
            'name' => 'CV. Makmur Jaya',
            'contact_person' => 'Andi',
            'phone' => '081298765432',
            'email' => 'sales@makmurjaya.com',
            'address' => 'Jl. Pahlawan No. 10, Surabaya',
        ]);
    }
}
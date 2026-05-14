<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::create([
            'code' => 'CUS001',
            'name' => 'PT. Otomotif Indonesia',
            'contact_person' => 'Rina',
            'phone' => '085612341234',
            'email' => 'procurement@otoindo.com',
            'address' => 'Kawasan Industri Cikarang',
        ]);
    }
}
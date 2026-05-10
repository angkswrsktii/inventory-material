<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name'           => 'PT. BME',
                'contact_person' => null,
                'phone'          => null,
                'email'          => null,
                'address'        => null,
                'npwp'           => null,
                'notes'          => null,
                'is_active'      => true,
            ],
            [
                'name'           => 'PT. BME P3',
                'contact_person' => null,
                'phone'          => null,
                'email'          => null,
                'address'        => null,
                'npwp'           => null,
                'notes'          => null,
                'is_active'      => true,
            ],
            [
                'name'           => 'PT. CPL',
                'contact_person' => null,
                'phone'          => null,
                'email'          => null,
                'address'        => null,
                'npwp'           => null,
                'notes'          => null,
                'is_active'      => true,
            ],
            [
                'name'           => 'PT. GDM',
                'contact_person' => null,
                'phone'          => null,
                'email'          => null,
                'address'        => null,
                'npwp'           => null,
                'notes'          => null,
                'is_active'      => true,
            ],
            [
                'name'           => 'PT. INDTA',
                'contact_person' => null,
                'phone'          => null,
                'email'          => null,
                'address'        => null,
                'npwp'           => null,
                'notes'          => null,
                'is_active'      => true,
            ],
            [
                'name'           => 'PT. SGS',
                'contact_person' => null,
                'phone'          => null,
                'email'          => null,
                'address'        => null,
                'npwp'           => null,
                'notes'          => null,
                'is_active'      => true,
            ],
            [
                'name'           => 'PT. STALLION',
                'contact_person' => null,
                'phone'          => null,
                'email'          => null,
                'address'        => null,
                'npwp'           => null,
                'notes'          => null,
                'is_active'      => true,
            ],
            [
                'name'           => 'PT. YK',
                'contact_person' => null,
                'phone'          => null,
                'email'          => null,
                'address'        => null,
                'npwp'           => null,
                'notes'          => null,
                'is_active'      => true,
            ],
        ];

        foreach ($customers as $data) {
            Customer::firstOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        $this->command->info('CustomerSeeder: ' . count($customers) . ' customer berhasil di-seed.');
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PicSeeder extends Seeder
{
    public function run(): void
    {
        $pics = [
            ['name' => 'Tama',  'position' => 'Gudang',    'is_active' => true],
            ['name' => 'Sakti', 'position' => 'Pemotong',  'is_active' => true],
        ];

        foreach ($pics as $pic) {
            DB::table('m_pics')->insertOrIgnore($pic);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            ['name' => 'MMT'],
            ['name' => 'MPR'],
            ['name' => 'Panasonic'],
        ];

        foreach ($projects as $project) {
            DB::table('m_project')->insertOrIgnore($project);
        }
    }
}

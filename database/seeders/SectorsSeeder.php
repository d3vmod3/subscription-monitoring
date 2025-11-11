<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sector;

class SectorsSeeder extends Seeder
{
    public function run(): void
    {
        $sectors = [
            ['name' => 'Sector A', 'description' => 'Main sector in North area', 'is_active' => true],
            ['name' => 'Sector B', 'description' => 'Secondary sector in East area', 'is_active' => true],
            ['name' => 'Sector C', 'description' => 'Industrial sector', 'is_active' => true],
            ['name' => 'Sector D', 'description' => 'Residential sector', 'is_active' => false],
        ];

        foreach ($sectors as $sector) {
            Sector::create($sector);
        }
    }
}

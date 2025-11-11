<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PassiveOpticalNetwork;
use App\Models\Sector;

class PonsSeeder extends Seeder
{
    public function run(): void
    {
        $sectors = Sector::all();

        foreach ($sectors as $sector) {
            for ($i = 1; $i <= 3; $i++) {
                PassiveOpticalNetwork::create([
                    'sector_id' => $sector->id,
                    'name' => $sector->name . ' - PON ' . $i,
                    'description' => 'PON ' . $i . ' under ' . $sector->name,
                    'is_active' => true,
                ]);
            }
        }
    }
}

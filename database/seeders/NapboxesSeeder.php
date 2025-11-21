<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Napbox;
use App\Models\PassiveOpticalNetwork;

class NapboxesSeeder extends Seeder
{
    public function run(): void
    {
        $pons = PassiveOpticalNetwork::all();

        foreach ($pons as $index=>$pon) {
            Napbox::create([
                'pon_id' => $pon->id,
                'napbox_code' => strtoupper('NAP-' . $pon->id),
                'name' => $pon->name . ' Napbox',
                'description' => 'Napbox connecting ' . $pon->name,
                'is_active' => $index % 2 === 0,
            ]);
        }
    }
}

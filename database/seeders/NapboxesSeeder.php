<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Napbox;
use App\Models\PassiveOpticalNetwork;
use App\Models\Splitter;

class NapboxesSeeder extends Seeder
{
    public function run(): void
    {
        $pons = PassiveOpticalNetwork::all();
        $splitters = Splitter::all();

        foreach ($pons as $pon) {
            foreach ($splitters as $index => $splitter) {

                Napbox::create([
                    'pon_id' => $pon->id,
                    'napbox_code' => strtoupper('NAP-' . $pon->id . '-' . $splitter->id),
                    'name' => $pon->name . ' - ' . $splitter->name . ' Napbox',
                    'description' => 'Napbox connecting ' . $pon->name . ' and ' . $splitter->name,
                    'is_active' => $index % 2 === 0,
                ]);
            }
        }
    }
}

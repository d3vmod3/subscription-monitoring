<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Splitter;
use Faker\Factory as Faker;
use App\Models\Napbox;
use Illuminate\Support\Facades\DB;

class SplittersSeeder extends Seeder
{
    public function run(): void
    {
        $splitters = [
            ['name' => 'Splitter 1', 'description' => 'Main splitter'],
            ['name' => 'Splitter 2', 'description' => 'Backup splitter'],
            ['name' => 'Splitter 3', 'description' => 'Secondary splitter'],
        ];
        $napboxIds = DB::table('napboxes')->pluck('id')->toArray();
        $faker = Faker::create();
        foreach ($splitters as $splitter) {
            Splitter::create([
                'name'        => $splitter['name'],
                'description' => $splitter['description'],
                'napbox_id'   => $faker->randomElement($napboxIds),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Splitter;

class SplittersSeeder extends Seeder
{
    public function run(): void
    {
        $splitters = [
            ['name' => 'Splitter 1', 'description' => 'Main splitter', 'is_active' => true],
            ['name' => 'Splitter 2', 'description' => 'Backup splitter', 'is_active' => true],
            ['name' => 'Splitter 3', 'description' => 'Secondary splitter', 'is_active' => false],
        ];

        foreach ($splitters as $splitter) {
            \App\Models\Splitter::create($splitter);
        }
    }
}

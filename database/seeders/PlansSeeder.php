<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $plans = [
            ['20 Mbps', 'Basic plan suitable for light usage', 'monthly', 499.00],
            ['40 Mbps', 'Standard plan for home users', 'monthly', 699.00],
            ['60 Mbps', 'Intermediate plan for multiple devices', 'monthly', 999.00],
            ['80 Mbps', 'High-speed plan for small offices', 'monthly', 1299.00],
            ['100 Mbps', 'Premium plan for heavy use', 'monthly', 1599.00],
        ];

        foreach ($plans as $plan) {
            DB::table('plans')->insert([
                'name' => $plan[0],
                'description' => $plan[1],
                'price' => $plan[3],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

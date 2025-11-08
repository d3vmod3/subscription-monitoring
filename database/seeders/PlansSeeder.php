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
            ['plan_name' => '20Mbps', 'plan_description' => 'Basic 20Mbps internet', 'subscription_interval' => 'monthly', 'price' => 499],
            ['plan_name' => '40Mbps', 'plan_description' => 'Standard 40Mbps internet', 'subscription_interval' => 'monthly', 'price' => 699],
            ['plan_name' => '60Mbps', 'plan_description' => 'Fast 60Mbps internet', 'subscription_interval' => 'monthly', 'price' => 899],
            ['plan_name' => '80Mbps', 'plan_description' => 'Ultra 80Mbps internet', 'subscription_interval' => 'monthly', 'price' => 1199],
            ['plan_name' => '100Mbps', 'plan_description' => 'Premium 100Mbps internet', 'subscription_interval' => 'monthly', 'price' => 1499],
        ];

        foreach ($plans as $plan) {
            DB::table('plans')->insert(array_merge($plan, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}

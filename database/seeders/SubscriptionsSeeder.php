<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubscriptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $subscriberIds = DB::table('subscribers')->pluck('id')->toArray();
        $splitter_ids = DB::table('splitters')->pluck('id')->toArray();
        $planIds = DB::table('plans')->pluck('id')->toArray();

        // Create e.g. 50 random subscriptions
        for ($i = 0; $i < 50; $i++) {
            $startDate = $faker->dateTimeBetween('-2 years', '-1 month');
            $status = $faker->randomElement(['active', 'inactive', 'disconnected']);

            DB::table('subscriptions')->insert([
                'subscriber_id' => $faker->randomElement($subscriberIds),
                'plan_id' => $faker->randomElement($planIds),
                'splitter_id' => $faker->randomElement($splitter_ids),
                'mikrotik_name' => 'MKT-' . Str::upper(Str::random(6)),
                'start_date' => $startDate,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

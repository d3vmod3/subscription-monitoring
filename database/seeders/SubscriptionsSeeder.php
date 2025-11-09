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
        $planIds = DB::table('plans')->pluck('id')->toArray();

        foreach ($subscriberIds as $subscriberId) {
            $startDate = $faker->dateTimeBetween('-2 years', '-1 month');
            $dueDate = (clone $startDate)->modify('+1 month');
            $status = $faker->randomElement(['active', 'inactive', 'disconnected']);

            DB::table('subscriptions')->insert([
                'subscriber_id' => null, // optional if Users is not yet linked
                'plan_id' => $faker->randomElement($planIds),
                'sector_id' => null,
                'mikrotik_name' => 'MKT-' . Str::upper(Str::random(6)),
                'start_date' => $startDate,
                'due_date' => $dueDate,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdvancePayment;
use App\Models\Subscription;
use Faker\Factory as Faker;

class AdvancePaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $subscriptions = Subscription::all();

        foreach ($subscriptions as $subscription) {
            $numAdvances = rand(0, 2);

            for ($i = 0; $i < $numAdvances; $i++) {
                AdvancePayment::create([
                    'subscription_id' => $subscription->id,
                    'amount' => $faker->randomFloat(2, 500, 3000),
                    'is_used' => $faker->boolean(50),
                    'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdvanceBillPayment;
use App\Models\Subscription;
use Faker\Factory as Faker;

class AdvanceBillPaymentsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $subscriptions = Subscription::all();

        foreach ($subscriptions as $subscription) {
            // 30% of subscriptions may have advance payments
            if ($faker->boolean(30)) {
                $amount = $faker->randomFloat(2, 1000, 5000);
                $isUsed = $faker->boolean(50);

                AdvanceBillPayment::create([
                    'subscription_id' => $subscription->id,
                    'amount' => $amount,
                    'is_used' => $isUsed,
                    'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

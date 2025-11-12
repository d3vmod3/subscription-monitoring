<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $subscriptions = DB::table('subscriptions')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->select('subscriptions.id as subscription_id', 'plans.price')
            ->get();

        $paymentMethodIds = DB::table('payment_methods')->pluck('id')->toArray();

        foreach ($subscriptions as $sub) {
            // Create 1–5 payments per subscription
            $paymentsCount = rand(1, 5);

            for ($i = 0; $i < $paymentsCount; $i++) {
                DB::table('payments')->insert([
                    'subscription_id' => $sub->subscription_id,
                    'payment_method_id' => $faker->randomElement($paymentMethodIds),
                    'reference_number' => $faker->optional()->numerify('REF-#####'),
                    'paid_at' => $faker->dateTimeBetween('-2 years', 'now'),
                    'amount' => $sub->price, // Amount is exactly the plan price
                    'payment_category' => $i === 0 ? 'advanced payment' : 'monthly bill',
                    'is_approved' => $faker->randomElement(['pending', 'approved']),
                    'is_first_payment' => $i === 0 ? true : false,
                    'is_discounted' => $faker->boolean(30), // 30% chance payment is discounted
                    'has_balance' => $faker->boolean(20), // 20% chance there's remaining balance
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

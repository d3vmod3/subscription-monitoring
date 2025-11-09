<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        $subscriptionIds = DB::table('subscriptions')->pluck('id')->toArray();
        $methodIds = DB::table('payment_methods')->pluck('id')->toArray();

        foreach ($subscriptionIds as $subscriptionId) {
            // 60% chance subscriber has made a payment
            if ($faker->boolean(60)) {
                $numPayments = $faker->numberBetween(1, 6); // up to 6 payments

                foreach (range(1, $numPayments) as $i) {
                    $paidAt = $faker->dateTimeBetween('-1 year', 'now');

                    DB::table('payments')->insert([
                        'subscription_id' => $subscriptionId,
                        'payment_method_id' => $faker->randomElement($methodIds),
                        'reference_number' => $faker->optional()->uuid(),
                        'paid_at' => $paidAt,
                        'amount' => $faker->randomElement([499, 699, 999, 1299, 1599]),
                        'payment_category' => $faker->randomElement(['advanced payment', 'monthly bill']),
                        'is_approved' => $faker->randomElement(['approved', 'pending']),
                        'created_at' => $paidAt,
                        'updated_at' => $paidAt,
                    ]);
                }
            }
        }
    }
}

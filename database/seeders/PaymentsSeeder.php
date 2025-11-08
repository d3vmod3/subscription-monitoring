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
        $subscriptions = DB::table('subscriptions')->get();
        $paymentMethods = DB::table('payment_methods')->pluck('id')->toArray();

        foreach ($subscriptions as $sub) {
            // Random chance to pay or not (some months unpaid)
            if ($faker->boolean(70)) { // 70% chance subscriber has paid
                DB::table('payments')->insert([
                    'subscription_id' => $sub->id,
                    'payment_method_id' => $faker->randomElement($paymentMethods),
                    'reference_number' => $faker->boolean(30) ? null : strtoupper($faker->bothify('??#####')), // null for Cash
                    'amount_paid' => DB::table('plans')->where('id', $sub->plan_id)->value('price'),
                    'paid_at' => $faker->dateTimeBetween($sub->start_date, min($sub->end_date, 'now')),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

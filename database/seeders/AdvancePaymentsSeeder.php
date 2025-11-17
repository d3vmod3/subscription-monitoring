<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdvancePayment;
use App\Models\Subscription;
use App\Models\PaymentMethod;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class AdvancePaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $subscriptions = Subscription::with('subscriber')->get();
        $paymentMethods = PaymentMethod::all();

        foreach ($subscriptions as $subscription) {
            $numPayments = rand(1, 3);

            for ($i = 1; $i <= $numPayments; $i++) {
                

                $isDiscounted = $faker->boolean(10);

                // Safely build account name from subscriber fields
                $subscriber = $subscription->subscriber;
                $subscriberName = null;

                if ($subscriber) {
                    if (isset($subscriber->full_name)) {
                        $subscriberName = $subscriber->full_name;
                    } elseif (isset($subscriber->first_name) && isset($subscriber->last_name)) {
                        $subscriberName = "{$subscriber->first_name} {$subscriber->last_name}";
                    }
                }

                AdvancePayment::create([
                    'subscription_id'   => $subscription->id,
                    'payment_method_id' => $paymentMethods->random()->id,
                    'user_id' => 3,
                    'account_name'      => $faker->boolean(80)
                        ? ($subscriberName ?? $faker->name())
                        : $faker->name(),
                    'reference_number'  => Str::upper(Str::random(10)),
                    'paid_at'           => $faker->dateTimeBetween('-6 months', 'now'),
                    'paid_amount'       => $subscription->plan->price,
                    'status'            => 'Approved',
                    'discount_amount'     => 0.00,
                    'remarks'           => $isDiscounted ? $faker->sentence() : null,
                    'is_used' => false,
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\PaymentMethod;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class PaymentsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $subscriptions = Subscription::with('subscriber')->get();
        $paymentMethods = PaymentMethod::all();

        foreach ($subscriptions as $subscription) {
            $numPayments = rand(1, 3);
            $startDate = now()->subMonths($numPayments);

            for ($i = 1; $i <= $numPayments; $i++) {
                $coverMonth = (clone $startDate)->addMonths($i - 1);

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

                Payment::create([
                    'subscription_id'   => $subscription->id,
                    'payment_method_id' => $paymentMethods->random()->id,
                    'account_name'      => $faker->boolean(80)
                        ? ($subscriberName ?? $faker->name())
                        : $faker->name(),
                    'reference_number'  => Str::upper(Str::random(10)),
                    'paid_at'           => $faker->dateTimeBetween('-6 months', 'now'),
                    'month_year_cover'  => $coverMonth->format('Y-m'),
                    'paid_amount'       => $faker->randomFloat(2, 500, 1500),
                    'status'            => 'Approved',
                    'is_discounted'     => $isDiscounted,
                    'remarks'           => $isDiscounted ? $faker->sentence() : null,
                ]);
            }
        }
    }
}

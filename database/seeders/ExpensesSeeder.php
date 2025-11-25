<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use App\Models\User;
use App\Models\PaymentMethod;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ExpensesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Ensure you have at least one user
        $userIds = User::pluck('id')->toArray();

        if (count($userIds) === 0) {
            $this->command->error("No users found. Seed users first.");
            return;
        }

        $paymentMethods = PaymentMethod::all();

        for ($i = 0; $i < 50; $i++) {
            Expense::create([
                'user_id' => $faker->randomElement($userIds),
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph(3),
                'payment_method_id' => $paymentMethods->random()->id,
                'amount' => $faker->randomFloat(2, 100, 10000),
                // Date between Jan 1, 2024 to Dec 31, 2025
                'date_time_issued' => Carbon::createFromTimestamp(
                    $faker->dateTimeBetween('2024-01-01 00:00:00', '2025-12-31 23:59:59')->getTimestamp()
                ),
                'status' => $faker->randomElement([
                    'Approved',
                    'Disapproved',
                    'Pending',
                ]),
            ]);
        }
    }
}

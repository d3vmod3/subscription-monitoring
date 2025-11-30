<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Always run these
        $this->call([
            PermissionsSeeder::class,
            RolesSeeder::class,
            UsersSeeder::class,
        ]);

        // If APP_DEBUG = true (local/dev environment), run more seeders
        if (config('app.debug')) {
            $this->call([
                SubscribersSeeder::class,
                PlansSeeder::class,
                PaymentMethodsSeeder::class,
                SectorsSeeder::class,
                PonsSeeder::class,
                NapboxesSeeder::class,
                SplittersSeeder::class,
                SubscriptionsSeeder::class,
                PaymentsSeeder::class,
                AdvancePaymentsSeeder::class,
                ExpensesSeeder::class,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            PermissionsSeeder::class,
            RolesSeeder::class,
            UsersSeeder::class,
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

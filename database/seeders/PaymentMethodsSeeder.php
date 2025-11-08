<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $methods = ['Cash', 'GCash', 'BDO', 'BPI', 'Maya'];

        foreach ($methods as $method) {
            DB::table('payment_methods')->insert([
                'name' => $method,
                'description' => $method.' payment',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SubscribersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 50; $i++) {
            DB::table('subscribers')->insert([
                'first_name' => $faker->firstName,
                'middle_name' => $faker->optional()->firstName,
                'last_name' => $faker->lastName,
                'birthdate' => $faker->date('Y-m-d', '2005-01-01'),
                'gender' => $faker->randomElement(['male','female','other']),
                'contact_number' => $faker->phoneNumber,
                'address' => $faker->address,
                'status' => $faker->randomElement(['active','inactive']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

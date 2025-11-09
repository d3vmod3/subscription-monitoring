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

        foreach (range(1, 50) as $i) {
            DB::table('subscribers')->insert([ // you can adjust later if you have multiple sectors
                'email' => $faker->unique()->safeEmail(),
                'first_name' => $faker->firstName(),
                'middle_name' => $faker->optional()->firstName(),
                'last_name' => $faker->lastName(),
                'birthdate' => $faker->dateTimeBetween('-50 years', '-18 years'),
                'gender' => $faker->randomElement(['male', 'female']),
                'contact_number' => $faker->phoneNumber(),
                'address_line_1' => $faker->streetAddress(),
                'address_line_2' => $faker->optional()->secondaryAddress(),
                'is_active' => $faker->boolean(90),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

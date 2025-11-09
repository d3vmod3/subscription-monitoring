<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'email' => 'admin1@example.com',
                'password' => Hash::make('password123'),
                'first_name' => 'Juan',
                'middle_name' => 'Dela',
                'last_name' => 'Cruz',
                'birthdate' => '1985-01-15',
                'gender' => 'male',
                'contact_number' => '09171234567',
                'address' => '123 Manila Street, Philippines',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'admin2@example.com',
                'password' => Hash::make('password123'),
                'first_name' => 'Maria',
                'middle_name' => 'Santos',
                'last_name' => 'Lopez',
                'birthdate' => '1990-05-10',
                'gender' => 'female',
                'contact_number' => '09179876543',
                'address' => '456 Quezon Avenue, Philippines',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'staff1@example.com',
                'password' => Hash::make('password123'),
                'first_name' => 'Pedro',
                'middle_name' => 'Reyes',
                'last_name' => 'Gomez',
                'birthdate' => '1992-09-20',
                'gender' => 'male',
                'contact_number' => '09171239876',
                'address' => '789 Cebu Street, Philippines',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

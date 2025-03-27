<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $users = [];

        for ($i = 1; $i <= 10; $i++) {
            $users[] = [
                'id' => $i,
                // 'name' => $faker->name,  // Nama random
                'email' => $faker->unique()->safeEmail,  // Email unik
                'password' => Hash::make('password123'), // Password terenkripsi
                'role' => $i === 1 ? 'admin' : 'customer', // User pertama admin, lainnya customer
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}

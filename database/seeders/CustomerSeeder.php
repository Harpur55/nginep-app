<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $customers = [];

        for ($i = 1; $i <= 20; $i++) {
            $customers[] = [
                'user_id' => rand(1, 10), // Sesuaikan dengan jumlah user di database
                'name' => $faker->name, // Nama random
                'phone' => '08' . rand(100000000, 999999999), // Nomor HP unik
                'address' => $faker->address, // Alamat lebih realistis
                'birth_date' => Carbon::now()->subYears(rand(18, 50))->format('Y-m-d'), // Umur antara 18-50 tahun
                'gender' => $faker->randomElement(['male', 'female']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('customer')->insert($customers);
    }
}

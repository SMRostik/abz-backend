<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Отримайте всі існуючі position_id
        $positionIds = DB::table('positions')->pluck('id')->toArray();

        foreach (range(1, 45) as $index) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => '+380' . $faker->numberBetween(100000000, 999999999),
                'position_id' => $faker->randomElement($positionIds),
                'photo' => $faker->imageUrl(70, 70, 'people', true, 'FakePhoto'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

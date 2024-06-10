<?php

namespace Database\Seeders;

use Faker\Generator as Faker;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        $users = [];

        for ($i = 0; $i < 50; $i++) {
            $users[] = [
                'name' => $faker->name(),
                'email' => $faker->email(),
                'mobile_no' => $faker->numerify(str_repeat('#', rand(10, 10))),
                'password' => $faker->password(minLength: 8),
                'gender' => $faker->randomElement(['male', 'other', 'female']),
                'role' => $faker->randomElement(['user', 'doctor']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        User::insert($users);

    }
}
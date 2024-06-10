<?php

namespace Database\Seeders;
use App\Models\Appointment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
            $appointments = [];
    
            for ($i = 0; $i < 50; $i++) {
                $appointments[] = [
                    'time' => $faker->time(),
                    'date' => $faker->date(),
                    'appointment_type' => $faker->randomElement(['general','operation','symtoms']),
                    'description' => $faker->sentence(),
                    'doctor_id' => $faker->numberBetween(3, 5),
                    'patient_id' => $faker->numberBetween(6, 50),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
    
            Appointment::insert($appointments);
        
    }
}
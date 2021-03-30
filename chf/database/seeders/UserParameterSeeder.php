<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserParameterSeeder extends Seeder

{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Oliver's parameters
        // systolic blood pressure
        DB::table('user_parameters')->insert([
            'user_id' => 2,
            'parameter_id' => 1,
            'measurement_times' => '3',
            'measurement_span' => 'week',
            'threshold_safety_max' => 110,
            'threshold_therapeutic_max' => 90,
        ]);

        // diastolic blood pressure
        DB::table('user_parameters')->insert([
            'user_id' => 2,
            'parameter_id' => 2,
            'measurement_times' => '3',
            'measurement_span' => 'week',
            'threshold_safety_max' => 70,
            'threshold_therapeutic_max' => 50,
        ]);

        // heart rate
        DB::table('user_parameters')->insert([
            'user_id' => 2,
            'parameter_id' => 3,
            'measurement_times' => '3',
            'measurement_span' => 'week',
            'threshold_safety_min' => 50,
            'threshold_safety_max' => 90,
            'threshold_therapeutic_max' => 80,
        ]);

        // SpO2
        DB::table('user_parameters')->insert([
            'user_id' => 2,
            'parameter_id' => 4,
            'measurement_times' => '3',
            'measurement_span' => 'week',
        ]);

        // Weight
        DB::table('user_parameters')->insert([
            'user_id' => 2,
            'parameter_id' => 5,
            'measurement_times' => '1',
            'measurement_span' => 'day',
            'threshold_therapeutic_max' => 85,
        ]);

        // Weight Change
        DB::table('user_parameters')->insert([
            'user_id' => 2,
            'parameter_id' => 6,
        ]);

        // ECG 
        DB::table('user_parameters')->insert([
            'user_id' => 2,
            'parameter_id' => 7,
            'measurement_times' => '1',
            'measurement_span' => 'week',
        ]);

        // for every parameter, each user has a 75 % chance
        // to have the parameter assigned to them
        for ($user_id = 3; $user_id <= 10; $user_id++) {
            for ($parameter_id = 1; $parameter_id <= 6; $parameter_id++) {
                if (rand() % 100 <= 75) {
                    DB::table('user_parameters')->insert([
                        'user_id' => $user_id,
                        'parameter_id' => $parameter_id,
                    ]);
                }
            }
        }
    }
}

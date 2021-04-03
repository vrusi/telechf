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
        for ($patientId = 2; $patientId <= 10; $patientId++) {
            // systolic blood pressure
            DB::table('user_parameters')->insert([
                'user_id' => $patientId,
                'parameter_id' => 1,
                'measurement_times' => '3',
                'measurement_span' => 'week',
                'threshold_safety_max' => 110,
                'threshold_therapeutic_max' => 90,
            ]);

            // diastolic blood pressure
            DB::table('user_parameters')->insert([
                'user_id' => $patientId,
                'parameter_id' => 2,
                'measurement_times' => '3',
                'measurement_span' => 'week',
                'threshold_safety_max' => 70,
                'threshold_therapeutic_max' => 50,
            ]);

            // heart rate
            DB::table('user_parameters')->insert([
                'user_id' => $patientId,
                'parameter_id' => 3,
                'measurement_times' => '3',
                'measurement_span' => 'week',
                'threshold_safety_min' => 50,
                'threshold_safety_max' => 90,
                'threshold_therapeutic_max' => 80,
            ]);

            // SpO2
            DB::table('user_parameters')->insert([
                'user_id' => $patientId,
                'parameter_id' => 4,
                'measurement_times' => '3',
                'measurement_span' => 'week',
            ]);

            // Weight
            DB::table('user_parameters')->insert([
                'user_id' => $patientId,
                'parameter_id' => 5,
                'measurement_times' => '1',
                'measurement_span' => 'day',
                'threshold_therapeutic_max' => 85,
            ]);

            // Weight Change
            DB::table('user_parameters')->insert([
                'user_id' => $patientId,
                'parameter_id' => 6,
            ]);

            // ECG 
            DB::table('user_parameters')->insert([
                'user_id' => $patientId,
                'parameter_id' => 7,
                'measurement_times' => '1',
                'measurement_span' => 'week',
            ]);
        }
    }
}

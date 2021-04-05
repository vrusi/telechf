<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $monthAgo = $now->copy()->subMonth();
        for ($patientId = 2; $patientId <= 10; $patientId++) {

            // daily measurements
            $weightPrevious = 0;
            $weightChange = 0;

            $day = $monthAgo->copy();
            for ($dayIndex = 0; $dayIndex <= 30; $dayIndex++) {
                $day->addDay();

                $weightCurrent = rand(7000, 8000) / 100;

                if ($weightPrevious) {
                    $weightChange = $weightCurrent - $weightPrevious;
                }

                $weightPrevious = $weightCurrent;

                $createdAt = $day->copy()->subMinutes(rand(30, 0));

                // weight
                DB::table('measurements')->insert([
                    'user_id' => $patientId,
                    'parameter_id' => 5,
                    'value' => $weightCurrent,
                    'swellings' => rand(2, 4),
                    'exercise_tolerance' => rand(2, 4),
                    'dyspnoea' => rand(2, 4),
                    'created_at' => $createdAt,

                    // 5 % chance at any alarm
                    'triggered_safety_alarm_min' => rand(0, 100) <= 5 ? true : false,
                    'triggered_safety_alarm_max' => rand(0, 100) <= 5 ? true : false,
                    'triggered_therapeutic_alarm_min' => rand(0, 100) <= 5 ? true : false,
                    'triggered_therapeutic_alarm_max' => rand(0, 100) <= 5 ? true : false,

                ]);

                // weight change
                if ($weightChange) {
                    DB::table('measurements')->insert([
                        'user_id' => $patientId,
                        'parameter_id' => 6,
                        'value' => $weightChange,
                        'swellings' => rand(2, 4),
                        'exercise_tolerance' => rand(2, 4),
                        'dyspnoea' => rand(2, 4),
                        'created_at' => $createdAt,

                        // 5 % chance at any alarm
                        'triggered_safety_alarm_min' => rand(0, 100) <= 5 ? true : false,
                        'triggered_safety_alarm_max' => rand(0, 100) <= 5 ? true : false,
                        'triggered_therapeutic_alarm_min' => rand(0, 100) <= 5 ? true : false,
                        'triggered_therapeutic_alarm_max' => rand(0, 100) <= 5 ? true : false,
                    ]);
                }
            }

            // three times weekly measurements
            $day = $monthAgo->copy();
            for ($dayIndex = 0; $dayIndex <= 30; $dayIndex += 2) {
                $day->addDays(2);

                // systolic pressure
                DB::table('measurements')->insert([
                    'user_id' => $patientId,
                    'parameter_id' => 1,
                    'value' => rand(100, 120),
                    'swellings' => rand(2, 4),
                    'exercise_tolerance' => rand(2, 4),
                    'dyspnoea' => rand(2, 4),
                    'created_at' =>  $day->copy()->subMinutes(rand(30, 0)),

                    // 5 % chance at any alarm
                    'triggered_safety_alarm_min' => rand(0, 100) <= 5 ? true : false,
                    'triggered_safety_alarm_max' => rand(0, 100) <= 5 ? true : false,
                    'triggered_therapeutic_alarm_min' => rand(0, 100) <= 5 ? true : false,
                    'triggered_therapeutic_alarm_max' => rand(0, 100) <= 5 ? true : false,
                ]);

                // diastolic pressure
                DB::table('measurements')->insert([
                    'user_id' => $patientId,
                    'parameter_id' => 2,
                    'value' => rand(60, 80),
                    'swellings' => rand(2, 4),
                    'exercise_tolerance' => rand(2, 4),
                    'dyspnoea' => rand(2, 4),
                    'created_at' =>  $day->copy()->subMinutes(rand(30, 0)),

                    // 5 % chance at any alarm
                    'triggered_safety_alarm_min' => rand(0, 100) <= 5 ? true : false,
                    'triggered_safety_alarm_max' => rand(0, 100) <= 5 ? true : false,
                    'triggered_therapeutic_alarm_min' => rand(0, 100) <= 5 ? true : false,
                    'triggered_therapeutic_alarm_max' => rand(0, 100) <= 5 ? true : false,
                ]);

                // heart rate
                DB::table('measurements')->insert([
                    'user_id' => $patientId,
                    'parameter_id' => 3,
                    'value' => rand(60, 85),
                    'swellings' => rand(2, 4),
                    'exercise_tolerance' => rand(2, 4),
                    'dyspnoea' => rand(2, 4),
                    'created_at' => $day->copy()->subMinutes(rand(30, 0)),

                    // 5 % chance at any alarm
                    'triggered_safety_alarm_min' => rand(0, 100) <= 5 ? true : false,
                    'triggered_safety_alarm_max' => rand(0, 100) <= 5 ? true : false,
                    'triggered_therapeutic_alarm_min' => rand(0, 100) <= 5 ? true : false,
                    'triggered_therapeutic_alarm_max' => rand(0, 100) <= 5 ? true : false,
                ]);

                // SpO2
                DB::table('measurements')->insert([
                    'user_id' => $patientId,
                    'parameter_id' => 4,
                    'value' => rand(9500, 9900) / 100,
                    'swellings' => rand(2, 4),
                    'exercise_tolerance' => rand(2, 4),
                    'dyspnoea' => rand(2, 4),
                    'created_at' => $day->copy()->subMinutes(rand(30, 0)),

                    // 5 % chance at any alarm
                    'triggered_safety_alarm_min' => rand(0, 100) <= 5 ? true : false,
                    'triggered_safety_alarm_max' => rand(0, 100) <= 5 ? true : false,
                    'triggered_therapeutic_alarm_min' => rand(0, 100) <= 5 ? true : false,
                    'triggered_therapeutic_alarm_max' => rand(0, 100) <= 5 ? true : false,
                ]);
            }
        }
    }
}

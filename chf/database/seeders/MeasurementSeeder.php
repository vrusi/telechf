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
        /* ----------------------------------------------------------------- */
        /* Oliver Raynolds measurements                                      */
        /* ----------------------------------------------------------------- */

        // most recent - alarm triggered --------------------------------------
        // systolic pressure
        DB::table('measurements')->insert([
            'user_id' => 2,
            'parameter_id' => 1,
            'value' => 123,
            'swellings' => 5,
            'exercise_tolerance' => 5,
            'dyspnoea' => 5,
            'triggered_safety_alarm' => true,
            'created_at' => Carbon::now()->subMinutes(15),
        ]);

        // diastolic blood pressure
        DB::table('measurements')->insert([
            'user_id' => 2,
            'parameter_id' => 2,
            'value' => 82,
            'swellings' => 5,
            'exercise_tolerance' => 5,
            'dyspnoea' => 5,
            'created_at' => Carbon::now()->subMinutes(10),
        ]);

        // heart rate
        DB::table('measurements')->insert([
            'user_id' => 2,
            'parameter_id' => 3,
            'value' => 100,
            'swellings' => 5,
            'exercise_tolerance' => 5,
            'dyspnoea' => 5,
            'triggered_therapeutic_alarm' => true,
            'created_at' => Carbon::now()->subMinutes(5),
        ]);

        // SpO2
        DB::table('measurements')->insert([
            'user_id' => 2,
            'parameter_id' => 4,
            'value' => 98,
            'swellings' => 5,
            'exercise_tolerance' => 5,
            'dyspnoea' => 5,
            'created_at' => Carbon::now()->subMinutes(20),
        ]);

        // weight
        DB::table('measurements')->insert([
            'user_id' => 2,
            'parameter_id' => 5,
            'value' => 79.3,
            'swellings' => 5,
            'exercise_tolerance' => 5,
            'dyspnoea' => 5,
            'created_at' => Carbon::now()->subMinutes(25),
        ]);

        // the rest of the measurements - no alarms triggered -----------------
        // daily - weight
        for ($days = 1; $days <= 10; $days++) {
            DB::table('measurements')->insert([
                'user_id' => 2,
                'parameter_id' => 5,
                'value' => 79 + rand(0, 150) / 100,
                'created_at' => Carbon::now()->subDays($days),
            ]);
        }

        // every 3 days - blood pressure and heart rate
        for ($days = 1; $days <= 10; $days += 3) {
            // systolic
            DB::table('measurements')->insert([
                'user_id' => 2,
                'parameter_id' => 1,
                'value' => rand(100, 120),
                'created_at' => Carbon::now()->subDays($days),
            ]);

            // diastolic
            DB::table('measurements')->insert([
                'user_id' => 2,
                'parameter_id' => 2,
                'value' => rand(60, 80),
                'created_at' => Carbon::now()->subDays($days),
            ]);

            // heart rate
            DB::table('measurements')->insert([
                'user_id' => 2,
                'parameter_id' => 3,
                'value' => rand(60, 100),
                'created_at' => Carbon::now()->subDays($days),
            ]);
        }

        // weekly - SpO2
        for ($days = 1; $days <= 10; $days += 7) {
            DB::table('measurements')->insert([
                'user_id' => 2,
                'parameter_id' => 4,
                'value' => rand(95, 99),
                'created_at' => Carbon::now()->subDays($days),
            ]);
        }

        /* ----------------------------------------------------------------- */
        /* other users - alarm measurements only                             */
        /* ----------------------------------------------------------------- */

        // Nancy Cook
        DB::table('measurements')->insert([
            'user_id' => 4,
            'parameter_id' => 1,
            'value' => 120,
            'exercise_tolerance' => 3,
            'dyspnoea' => 3,
            'created_at' => Carbon::now()->subDays(3),
        ]);

        DB::table('measurements')->insert([
            'user_id' => 4,
            'parameter_id' => 2,
            'value' => 81,
            'exercise_tolerance' => 3,
            'dyspnoea' => 3,
            'created_at' => Carbon::now()->subDays(3),
        ]);

        DB::table('measurements')->insert([
            'user_id' => 4,
            'parameter_id' => 3,
            'value' => 62,
            'exercise_tolerance' => 3,
            'dyspnoea' => 3,
            'created_at' => Carbon::now()->subDays(3),
        ]);

        DB::table('measurements')->insert([
            'user_id' => 4,
            'parameter_id' => 4,
            'value' => 76,
            'exercise_tolerance' => 5,
            'dyspnoea' => 5,
            'triggered_therapeutic_alarm' => true,
            'checked' => true,
            'created_at' => Carbon::now()->subDays(3),
        ]);

        DB::table('measurements')->insert([
            'user_id' => 4,
            'parameter_id' => 5,
            'value' => 81.9,
            'exercise_tolerance' => 3,
            'dyspnoea' => 3,
            'created_at' => Carbon::now()->subDays(3),
        ]);

        // Ethan Archer
        DB::table('measurements')->insert([
            'user_id' => 3,
            'parameter_id' => 1,
            'value' => 121,
            'swellings' => 5,
            'dyspnoea' => 5,
            'created_at' => Carbon::now()->subDays(10),
        ]);

        DB::table('measurements')->insert([
            'user_id' => 3,
            'parameter_id' => 2,
            'value' => 82,
            'swellings' => 5,
            'dyspnoea' => 5,
            'created_at' => Carbon::now()->subDays(10),
        ]);

        DB::table('measurements')->insert([
            'user_id' => 3,
            'parameter_id' => 3,
            'value' => 70,
            'swellings' => 5,
            'dyspnoea' => 5,
            'created_at' => Carbon::now()->subDays(10),
        ]);

        DB::table('measurements')->insert([
            'user_id' => 3,
            'parameter_id' => 4,
            'value' => 96,
            'swellings' => 5,
            'dyspnoea' => 5,
            'created_at' => Carbon::now()->subDays(10),
        ]);

        DB::table('measurements')->insert([
            'user_id' => 3,
            'parameter_id' => 5,
            'value' => 73,
            'swellings' => 5,
            'dyspnoea' => 5,
            'triggered_therapeutic_alarm' => true,
            'checked' => true,
            'created_at' => Carbon::now()->subDays(10),
        ]);

        // Peter Sharp
        DB::table('measurements')->insert([
            'user_id' => 8,
            'parameter_id' => 1,
            'value' => 145,
            'swellings' => 5,
            'exercise_tolerance' => 5,
            'triggered_therapeutic_alarm' => true,
            'checked' => true,
            'created_at' => Carbon::now()->subDays(13),
        ]);

        DB::table('measurements')->insert([
            'user_id' => 8,
            'parameter_id' => 2,
            'value' => 95,
            'swellings' => 5,
            'exercise_tolerance' => 5,
            'triggered_therapeutic_alarm' => true,
            'checked' => true,
            'created_at' => Carbon::now()->subDays(13),
        ]);

        DB::table('measurements')->insert([
            'user_id' => 8,
            'parameter_id' => 3,
            'value' => 90,
            'swellings' => 5,
            'exercise_tolerance' => 5,
            'triggered_therapeutic_alarm' => true,
            'checked' => true,
            'created_at' => Carbon::now()->subDays(13),
        ]);

        DB::table('measurements')->insert([
            'user_id' => 8,
            'parameter_id' => 4,
            'value' => 98,
            'swellings' => 5,
            'exercise_tolerance' => 5,
            'created_at' => Carbon::now()->subDays(13),
        ]);

        DB::table('measurements')->insert([
            'user_id' => 8,
            'parameter_id' => 5,
            'value' => 84.5,
            'swellings' => 5,
            'exercise_tolerance' => 5,
            'created_at' => Carbon::now()->subDays(13),
        ]);

        // Adam White
        DB::table('measurements')->insert([
            'user_id' => 10,
            'parameter_id' => 1,
            'value' => 115,
            'swellings' => 4,
            'exercise_tolerance' => 4,
            'dyspnoea' => 5,
            'triggered_therapeutic_alarm' => true,
            'checked' => true,
            'created_at' => Carbon::now()->subDays(15),
        ]);

        DB::table('measurements')->insert([
            'user_id' => 10,
            'parameter_id' => 2,
            'value' => 75,
            'swellings' => 4,
            'exercise_tolerance' => 4,
            'dyspnoea' => 5,
            'triggered_therapeutic_alarm' => true,
            'checked' => true,
            'created_at' => Carbon::now()->subDays(15),
        ]);

        DB::table('measurements')->insert([
            'user_id' => 10,
            'parameter_id' => 3,
            'value' => 75,
            'swellings' => 4,
            'exercise_tolerance' => 4,
            'dyspnoea' => 4,
            'created_at' => Carbon::now()->subDays(15),
        ]);

        DB::table('measurements')->insert([
            'user_id' => 10,
            'parameter_id' => 4,
            'value' => 95,
            'swellings' => 4,
            'exercise_tolerance' => 4,
            'dyspnoea' => 4,
            'created_at' => Carbon::now()->subDays(15),
        ]);

        DB::table('measurements')->insert([
            'user_id' => 10,
            'parameter_id' => 5,
            'value' => 88.7,
            'swellings' => 4,
            'exercise_tolerance' => 4,
            'dyspnoea' => 4,
            'created_at' => Carbon::now()->subDays(15),
        ]);
    }
}

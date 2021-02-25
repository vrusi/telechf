<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UserConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            /* everyone has congrestive heart failure */
            DB::table('user_conditions')->insert([
                'user_id' => $i,
                'condition_id' => 1,
            ]);

            /* additional medical condition */
            if (rand() % 100 <= 25) {
                DB::table('user_conditions')->insert([
                    'user_id' => $i,
                    'condition_id' => rand(2,7),
                ]);
            }
        }
    }
}

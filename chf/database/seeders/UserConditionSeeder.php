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
        DB::table('user_conditions')->insert([
            'user_id' => 2,
            'condition_id' => 2,
        ]);

        for ($user_id = 2; $user_id <= 10; $user_id++) {
            /* everyone has congestive heart failure */
            DB::table('user_conditions')->insert([
                'user_id' => $user_id,
                'condition_id' => 1,
            ]);

            /* additional medical condition */
            if (rand() % 100 <= 25) {
                DB::table('user_conditions')->insert([
                    'user_id' => $user_id,
                    'condition_id' => rand(2,6),
                ]);
            }
        }
    }
}

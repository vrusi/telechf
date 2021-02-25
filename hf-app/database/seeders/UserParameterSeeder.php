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
        // for every parameter, each user has a 75 % chance
        // to have the parameter assigned to them
        for ($user_id = 2; $user_id <= 10; $user_id++) {
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

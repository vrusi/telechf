<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserDrugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Oliver Reynolds takes has all 3 presciptions */
        for ($drug_id = 1; $drug_id <= 3; $drug_id++) {
            DB::table('user_drugs')->insert([
                'user_id' => 2,
                'drug_id' => $drug_id,
            ]);
        }

        /* 75 % chance other patients take medication */
        if (rand() % 100 <= 75) {
            DB::table('user_drugs')->insert([
                'user_id' => rand(3, 9),
                'drug_id' => rand(1, 3),
            ]);
        }
    }
}

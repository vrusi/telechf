<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('conditions')->insert([
            'name' => 'congestive heart failure',
        ]);

        DB::table('conditions')->insert([
            'name' => 'rheumatoid arthritis',
        ]);

        DB::table('conditions')->insert([
            'name' => 'obesity',
        ]);

        DB::table('conditions')->insert([
            'name' => 'morbid obesity',
        ]);

        DB::table('conditions')->insert([
            'name' => 'overweight',
        ]);

        DB::table('conditions')->insert([
            'name' => 'kidney stones',
        ]);

    }
}

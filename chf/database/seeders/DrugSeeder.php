<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DrugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('drugs')->insert([
            'name' => 'captopril'
        ]);

        DB::table('drugs')->insert([
            'name' => 'losartan',
        ]);

        DB::table('drugs')->insert([
            'name' => 'leflunomide',
        ]);
    }
}

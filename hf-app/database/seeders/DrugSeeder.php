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
            'name' => 'captopril',
            'dosage_volume' => 12.5,
            'dosage_unit' => 'mg',
            'dosage_times' => 3,
            'dosage_span' => 'day',
        ]);

        DB::table('drugs')->insert([
            'name' => 'losartan',
            'dosage_volume' => 50,
            'dosage_unit' => 'mg',
            'dosage_times' => 1,
            'dosage_span' => 'day',
        ]);

        DB::table('drugs')->insert([
            'name' => 'leflunomide',
            'dosage_volume' => 100,
            'dosage_unit' => 'mg',
            'dosage_times' => 1,
            'dosage_span' => 'day',
        ]);
    }
}

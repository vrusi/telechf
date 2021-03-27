<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contacts')->insert([
            'titles_prefix' => 'Dr',
            'name' => 'Cole',
            'surname' => 'Howard',
            'email' => 'cole.howard@gp.com',
            'mobile' => '+447700900227',
            'type' => 'general practitioner',
        ]);

        DB::table('contacts')->insert([
            'titles_prefix' => 'Dr',
            'name' => 'Jane',
            'surname' => 'Watts',
            'email' => 'jwatts@cardio.com',
            'mobile' => '+447700900915',
            'type' => 'cardiologist',
        ]);
    }
}

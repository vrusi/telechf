<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('messages')->insert([
            'sender_id' => 1,
            'receiver_id' => 2,
            'value' => 'Please remember to take your blood pressure measurement today.',
        ]);

        DB::table('messages')->insert([
            'sender_id' => 2,
            'receiver_id' => 1,
            'value' => 'Sorry, I was busy. I have taken the measurement now.',
        ]);
    }
}

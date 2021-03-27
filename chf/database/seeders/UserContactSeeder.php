<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($user_id = 2; $user_id <= 10; $user_id++) {
            for ($contact_id = 1; $contact_id <= 2; $contact_id++) {
                DB::table('user_contacts')->insert([
                    'user_id' => $user_id,
                    'contact_id' => $contact_id,
                ]);
            }
        }
    }
}

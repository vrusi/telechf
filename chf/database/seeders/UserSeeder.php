<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Alice',
            'surname' => 'Stone',
            'email' => 'coordinator@gmail.com',
            'is_coordinator' => true,
            'password' => Hash::make('coordinator'),
        ]);

        $min = strtotime("80 years ago");
        $max = strtotime("55 years ago");

        DB::table('users')->insert([
            'name' => 'Oliver',
            'surname' => 'Reynolds',
            'email' => 'patient@gmail.com',
            'mobile' => '+447700900290',
            'password' => Hash::make('patient'),
            'sex' => 'male',
            'dob' => date('Y-m-d', mt_rand($min, $max)),
            'height' => 182,
            'weight' => 79,
            'recommendations' => '<ul><li>Do not smoke.</li><li>Exercise 3 times a week.</li></ul>',
            'coordinator_id' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'Ethan',
            'surname' => 'Archer',
            'email' => 'earcher@gmail.com',
            'mobile' => '+447700900795',
            'password' => Hash::make('ethanarcher'),
            'sex' => 'male',
            'dob' => date('Y-m-d', mt_rand($min, $max)),
            'height' => 175,
            'weight' => 70,
            'coordinator_id' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'Nancy',
            'surname' => 'Cook',
            'email' => 'ncook@gmail.com',
            'mobile' => '+447700900664',
            'password' => Hash::make('nancycook'),
            'sex' => 'female',
            'dob' => date('Y-m-d', mt_rand($min, $max)),
            'height' => 168,
            'weight' => 82,
            'coordinator_id' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'Jordan',
            'surname' => 'Fletcher',
            'email' => 'jfletcher@gmail.com',
            'mobile' => '+447700900254',
            'password' => Hash::make('jordanfletcher'),
            'sex' => 'female',
            'dob' => date('Y-m-d', mt_rand($min, $max)),
            'height' => 172,
            'weight' => 72,
            'coordinator_id' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'Brandon',
            'surname' => 'Olson',
            'email' => 'bolson@gmail.com',
            'mobile' => '+447700900625',
            'password' => Hash::make('brandonolson'),
            'sex' => 'male',
            'dob' => date('Y-m-d', mt_rand($min, $max)),
            'height' => 177,
            'weight' => 79,
            'coordinator_id' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'Flynn',
            'surname' => 'Robinson',
            'email' => 'frobinson@gmail.com',
            'mobile' => '+447700900777',
            'password' => Hash::make('flynnrobinson'),
            'sex' => 'male',
            'dob' => date('Y-m-d', mt_rand($min, $max)),
            'height' => 171,
            'weight' => 55,
            'coordinator_id' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'Peter',
            'surname' => 'Sharp',
            'email' => 'psharp@gmail.com',
            'mobile' => '+447700900498',
            'password' => Hash::make('petersharp'),
            'sex' => 'male',
            'dob' => date('Y-m-d', mt_rand($min, $max)),
            'height' => 179,
            'weight' => 84,
            'coordinator_id' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'Marley',
            'surname' => 'Webb',
            'email' => 'mwebb@gmail.com',
            'mobile' => '+447700900748',
            'password' => Hash::make('marleywebb'),
            'sex' => 'female',
            'dob' => date('Y-m-d', mt_rand($min, $max)),
            'height' => 165,
            'weight' => 75,
            'coordinator_id' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'Adam',
            'surname' => 'White',
            'email' => 'awhite@gmail.com',
            'mobile' => '+447700900136',
            'password' => Hash::make('adamwhite'),
            'sex' => 'male',
            'dob' => date('Y-m-d', mt_rand($min, $max)),
            'height' => 177,
            'weight' => 89,
            'coordinator_id' => 1,
        ]);
    }
}

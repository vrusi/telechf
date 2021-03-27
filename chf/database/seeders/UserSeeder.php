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
            'mobile' => '+447700900139',
            'is_coordinator' => true,
            'password' => Hash::make('coordinator'),
        ]);

        DB::table('users')->insert([
            'name' => 'Oliver',
            'surname' => 'Reynolds',
            'email' => 'patient@gmail.com',
            'mobile' => '+447700900290',
            'password' => Hash::make('patient'),
            'sex' => 'male',
            'age' => 67,
            'height' => 182,
            'weight' => 79,
            'coordinator_id' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'Ethan',
            'surname' => 'Archer',
            'email' => 'earcher@gmail.com',
            'mobile' => '+447700900795',
            'password' => Hash::make('ethanarcher'),
            'sex' => 'male',
            'age' => 74,
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
            'age' => 68,
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
            'age' => 66,
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
            'age' => 70,
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
            'age' => 73,
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
            'age' => 68,
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
            'age' => 74,
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
            'age' => 68,
            'height' => 177,
            'weight' => 89,
            'coordinator_id' => 1,
        ]);
    }
}

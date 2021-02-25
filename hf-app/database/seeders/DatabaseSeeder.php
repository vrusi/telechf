<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ConditionSeeder::class,
            UserConditionSeeder::class,
            DrugSeeder::class,
            UserDrugSeeder::class,
            ParameterSeeder::class,
            UserParameterSeeder::class,
            ContactSeeder::class,
            UserContactSeeder::class,
            MessageSeeder::class,
        ]);
    }
}

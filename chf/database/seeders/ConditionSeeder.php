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
            'name' => 'Congestive heart failure',
            'description' => 'Congestive heart failure (CHF) is a chronic progressive condition that affects the pumping power of your heart muscle. While often referred to simply as heart failure, CHF specifically refers to the stage in which fluid builds up within the heart and causes it to pump inefficiently. You have four heart chambers.',
        ]);

        DB::table('conditions')->insert([
            'name' => 'Rheumatoid arthritis',
            'description' => 'Rheumatoid arthritis (RA) is an autoimmune disease that can cause joint pain and damage throughout your body. The joint damage that RA causes usually happens on both sides of the body. So, if a joint is affected in one of your arms or legs, the same joint in the other arm or leg will probably be affected, too.',
        ]);

        DB::table('conditions')->insert([
            'name' => 'Obesity',
            'description' => 'Obesity is a complex disease involving an excessive amount of body fat. Obesity isn\'t just a cosmetic concern. It is a medical problem that increases your risk of other diseases and health problems, such as heart disease, diabetes, high blood pressure and certain cancers.',
        ]);

        DB::table('conditions')->insert([
            'name' => 'Morbid obesity',
            'description' => 'Morbid obesity is a condition in which you have a body mass index (BMI) higher than 35. BMI is used to estimate body fat and can help determine if you are at a healthy body weight for your size. BMI is not a perfect measurement but it does help give a general idea of ideal weight ranges for height.',
        ]);

        DB::table('conditions')->insert([
            'name' => 'Overweight',
            'description' => 'Being overweight or fat is having more body fat than is optimally healthy. Being overweight is especially common where food supplies are plentiful and lifestyles are sedentary. A healthy body requires a minimum amount of fat for proper functioning of the hormonal, reproductive, and immune systems, as thermal insulation, as shock absorption for sensitive areas, and as energy for future use; however, the accumulation of too much storage fat can impair movement, flexibility, and alter the appearance of the body.',
        ]);

        DB::table('conditions')->insert([
            'name' => 'Kidney stones',
            'description' => 'Kidney stones (also called renal calculi, nephrolithiasis or urolithiasis) are hard deposits made of minerals and salts that form inside your kidneys. Diet, excess body weight, some medical conditions, and certain supplements and medications are among the many causes of kidney stones. Kidney stones can affect any part of your urinary tract — from your kidneys to your bladder. Often, stones form when the urine becomes concentrated, allowing minerals to crystallize and stick together.',
        ]);

    }
}

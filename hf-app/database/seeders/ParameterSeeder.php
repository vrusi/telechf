<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('parameters')->insert([
            'name' => 'systolic blood pressure',
            'unit' => 'mmHg',
            'measurement_times' => '3',
            'measurement_span' => 'week',
            'threshold_max' => 120,
            'instructions' => '**Be still.** Do not smoke, drink caffeinated beverages or exercise within 30 minutes before measuring your blood pressure. Empty your bladder and ensure at least 5 minutes of quiet rest before measurements.\n\n**Sit correctly.** Sit with your back straight and supported (on a dining chair, rather than a sofa). Your feet should be flat on the floor and your legs should not be crossed. Your arm should be supported on a flat surface (such as a table) with the upper arm at heart level. Make sure the bottom of the cuff is placed directly above the bend of the elbow. Check your monitor\'s instructions for an illustration or have your healthcare provider show you how.\n\n**Measure at the same time every day.** It’s important to take the readings at the same time each day, such as morning and evening. It is best to take the readings daily however ideally beginning 2 weeks after a change in treatment and during the week before your next appointment.\n\n**Do not take the measurement over clothes.**',
        ]);

        DB::table('parameters')->insert([
            'name' => 'diastolic blood pressure',
            'unit' => 'mmHg',
            'measurement_times' => '3',
            'measurement_span' => 'week',
            'threshold_max' => 80,
            'instructions' => '**Be still.** Do not smoke, drink caffeinated beverages or exercise within 30 minutes before measuring your blood pressure. Empty your bladder and ensure at least 5 minutes of quiet rest before measurements.\n\n**Sit correctly.** Sit with your back straight and supported (on a dining chair, rather than a sofa). Your feet should be flat on the floor and your legs should not be crossed. Your arm should be supported on a flat surface (such as a table) with the upper arm at heart level. Make sure the bottom of the cuff is placed directly above the bend of the elbow. Check your monitor\'s instructions for an illustration or have your healthcare provider show you how.\n\n**Measure at the same time every day.** It’s important to take the readings at the same time each day, such as morning and evening. It is best to take the readings daily however ideally beginning 2 weeks after a change in treatment and during the week before your next appointment.\n\n**Do not take the measurement over clothes.**',
        ]);

        DB::table('parameters')->insert([
            'name' => 'heart rate',
            'unit' => 'bps',
            'measurement_times' => '3',
            'measurement_span' => 'week',
            'threshold_min' => 60,
            'threshold_max' => 100,
            'instructions' => 'Do not measure your heart rate within one to two hours after exercise or a stressful event. Your heart rate can stay elevated after strenuous activities.\n\nWait an hour after consuming caffeine, which can cause heart palpitations and make your heart rate rise.\n\nDo not take the reading after you have been sitting or standing for a long period, which can affect your heart rate.',
        ]);

        DB::table('parameters')->insert([
            'name' => 'spo2',
            'unit' => '%',
            'measurement_times' => '1',
            'measurement_span' => 'week',
            'threshold_min' => 95,
            'instructions' => 'Remove any nail polish from the finger you will use to take the measurement - always use the same finger.\n\nPlace the place oximeter on the finger and start the measurement. You may feel a small amount of pressure, but no pain or pinching.'
        ]);

        DB::table('parameters')->insert([
            'name' => 'weight',
            'unit' => 'kg',
            'measurement_times' => '1',
            'measurement_span' => 'day',
            'threshold_max' => 2,
            'instructions' => 'For the most accurate weight, weigh yourself first thing in the morning.\n\nBe consistent when you weigh yourself. Weigh yourself at the same time. If you go to the bathroom before you weigh yourself, go before you do it again next time. You can weigh yourself naked every time or try wearing the same clothes.',
        ]);

        DB::table('parameters')->insert([
            'name' => 'ecg',
            'measurement_times' => '1',
            'measurement_span' => 'week',
        ]);
    }
}

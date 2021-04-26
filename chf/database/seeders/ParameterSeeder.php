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
            'name' => 'Systolic Blood Pressure',
            'unit' => 'mmHg',
            'measurement_times' => '3',
            'measurement_span' => 'week',
            'threshold_max' => 120,
            'instructions_en' => '<p><b>Be still.</b> Do not smoke, drink caffeinated beverages or exercise within 30 minutes before measuring your blood pressure. Empty your bladder and ensure at least 5 minutes of quiet rest before measurements.</p><p><b>Sit correctly.</b> Sit with your back straight and supported (on a dining chair, rather than a sofa). Your feet should be flat on the floor and your legs should not be crossed. Your arm should be supported on a flat surface (such as a table) with the upper arm at heart level. Make sure the bottom of the cuff is placed directly above the bend of the elbow. Check your monitor\'s instructions for an illustration.</p><p><b>Measure at the same time every day.</b> It’s important to take the readings at the same time each day, such as morning and evening.</p><p><b>Do not take the measurement over clothes.</b></p>',
            'instructions_sk' => '<p><b>Nehýbte sa.</b> Nefajčite, nepite kofeinové nápoje a necvičte aspoň 30 minút pred meraním krvného tlaku. Vyprázdnite močový mechúr a zaistite si aspoň 5 minút tichého odpočinku pred meraním.</p><p><b>Seďte správne.</b> Seďte s vystretým chrbtom a opretí (skôr na kuchynskej stoličke než na kresle). Chodidlá položte plocho na podlahu a nohy neprekladajte. Ruku položte na plochý povrch (napríklad na stôl) tak, aby Vaša horná ruka bola vo výške srdca. Uistite sa, že spodok tlakomeru je priamo nad zahnutím lakťa. Skontrolujte, či Váš tlakomer nemá inštrukcie s ilustráciami.</p><p><b>Merajte v rovnaký čas každý deň.</b> Je dôležité merať si tlak v rovnaký čas každý deň, napríklad každé ráno alebo každý večer.</p><p><b>Tlakomer nesmie byť na oblečení.</b></p>',
        ]);

        DB::table('parameters')->insert([
            'name' => 'Diastolic Blood Pressure',
            'unit' => 'mmHg',
            'measurement_times' => '3',
            'measurement_span' => 'week',
            'threshold_max' => 80,
            'instructions_en' => '<p><b>Be still.</b> Do not smoke, drink caffeinated beverages or exercise within 30 minutes before measuring your blood pressure. Empty your bladder and ensure at least 5 minutes of quiet rest before measurements.</p><p><b>Sit correctly.</b> Sit with your back straight and supported (on a dining chair, rather than a sofa). Your feet should be flat on the floor and your legs should not be crossed. Your arm should be supported on a flat surface (such as a table) with the upper arm at heart level. Make sure the bottom of the cuff is placed directly above the bend of the elbow. Check your monitor\'s instructions for an illustration.</p><p><b>Measure at the same time every day.</b> It’s important to take the readings at the same time each day, such as morning and evening.</p><p><b>Do not take the measurement over clothes.</b></p>',
            'instructions_sk' => '<p><b>Nehýbte sa.</b> Nefajčite, nepite kofeinové nápoje a necvičte aspoň 30 minút pred meraním krvného tlaku. Vyprázdnite močový mechúr a zaistite si aspoň 5 minút tichého odpočinku pred meraním.</p><p><b>Seďte správne.</b> Seďte s vystretým chrbtom a opretí (skôr na kuchynskej stoličke než na kresle). Chodidlá položte plocho na podlahu a nohy neprekladajte. Ruku položte na plochý povrch (napríklad na stôl) tak, aby Vaša horná ruka bola vo výške srdca. Uistite sa, že spodok tlakomeru je priamo nad zahnutím lakťa. Skontrolujte, či Váš tlakomer nemá inštrukcie s ilustráciami.</p><p><b>Merajte v rovnaký čas každý deň.</b> Je dôležité merať si tlak v rovnaký čas každý deň, napríklad každé ráno alebo každý večer.</p><p><b>Tlakomer nesmie byť na oblečení.</b></p>',
        ]);

        DB::table('parameters')->insert([
            'name' => 'Heart Rate',
            'unit' => 'bpm',
            'measurement_times' => '3',
            'measurement_span' => 'week',
            'threshold_min' => 60,
            'threshold_max' => 100,
            'instructions_en' => '<p>Do not measure your heart rate within one to two hours after exercise or a stressful event. Your heart rate can stay elevated after strenuous activities.</p><p>Wait an hour after consuming caffeine, which can cause heart palpitations and make your heart rate rise.</p><p>Do not take the reading after you have been sitting or standing for a long period, which can affect your heart rate.</p>',
            'instructions_sk' => '<p>Nemerajte svoj tep srdca aspoň hodinu - dve po cvičení alebo po stresujúcej udalosti. Váš tep môže ostať zvýšený po náročných aktivitách.</p><p>Počkajte hodinu po konzumovaní kofeínu, keďže môže spôsobiť búšenie srdca a zvýšiť tep.</p><p>Nemerajte sa po tom, čo ste sedeli alebo stáli dlhú dobu, čo môže ovplyvniť Váš tep srdca.</p>',
        ]);

        DB::table('parameters')->insert([
            'name' => 'SpO2',
            'unit' => '%',
            'measurement_times' => '1',
            'measurement_span' => 'week',
            'threshold_min' => 95,
            'instructions_en' => '<p>Remove any nail polish from the finger you will use to take the measurement - always use the same finger.</p><p>Place the place oximeter on the finger and start the measurement. You may feel a small amount of pressure, but no pain or pinching.</p>',
            'instructions_sk' => '<p>Odstráňte lak z nechta, na ktorom budete vykonávať meranie - vždy používajte rovnaký prst.</p><p>Nasaďte si oxymeter na prst a začnite meranie. Môžete cítiť malý tlak, ale nemali by ste cítiť bolesť či zovretie.</p>',
        ]);

        DB::table('parameters')->insert([
            'name' => 'Weight',
            'unit' => 'kg',
            'measurement_times' => '1',
            'measurement_span' => 'day',
            'instructions_en' => '<p>For the most accurate weight, weigh yourself first thing in the morning.</p><p>Be consistent when you weigh yourself. Weigh yourself at the same time. If you go to the bathroom before you weigh yourself, go before you do it again next time. You can weigh yourself naked every time or try wearing the same clothes.</p>',
            'instructions_sk' => '<p>Pre najpresnejšiu hmotnosť sa merajte ráno po zobudení.</p><p>Buďte konzistentní keď sa vážite. Vážte sa v rovnaký čas dňa. Ak pred odvážením sa použijete toaletu, použite ju znova nabudúce pred vážením sa. Môžete sa vážiť zakaždým nahí alebo skúste mať oblečené vždy rovnaké oblečenie.</p>',
        ]);

        DB::table('parameters')->insert([
            'name' => 'Weight Change',
            'unit' => 'kg',
            'measurement_times' => '1',
            'measurement_span' => 'day',
            'threshold_max' => 2,
            'fillable' => false,
        ]);

        DB::table('parameters')->insert([
            'name' => 'ECG',
            'measurement_times' => '1',
            'measurement_span' => 'week',
            'unit' => 'mV',
            'instructions_en' => 'N/A',
            'instructions_sk' => 'N/A',
        ]);
    }
}

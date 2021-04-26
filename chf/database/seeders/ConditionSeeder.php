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
            'description_en' => 'Congestive heart failure (CHF) is a chronic progressive condition that affects the pumping power of your heart muscle. While often referred to simply as heart failure, CHF specifically refers to the stage in which fluid builds up within the heart and causes it to pump inefficiently.',
            'description_sk' => 'Kongestívne zlyhanie srdca (CHF - congestive heart failure) je chronický progresívny stav, ktorý ovplyvňuje čerpaciu silu srdcového svalu. Často sa označuje len ako srdcové zlyhanie, CHF konkrétne označuje to štádium, v ktorom sa tekutina zhromažďuje vo vnútri srdca, čo spôsobuje neefektívne čerpanie.',
        ]);

        DB::table('conditions')->insert([
            'name' => 'Rheumatoid arthritis',
            'description_en' => 'Rheumatoid arthritis (RA) is an autoimmune disease that can cause joint pain and damage throughout your body. The joint damage that RA causes usually happens on both sides of the body. So, if a joint is affected in one of your arms or legs, the same joint in the other arm or leg will probably be affected, too.',
            'description_sk' => 'Reumatoidná artritída (RA) je autoimunitné ochorenie, môže spôsobiť bolesť kĺbov a poškodenia po celom tele. Poškodenie kĺbov, ktoré spôsobuje RA, sa väčšinou deje na oboch stranách tela. Teda, ak je kĺb postihnutý v jednej z Vašich rúk či nôh, rovnaký kĺb v druhej ruke či nohe bude pravdepodobne postihnutý tiež.',
        ]);

        DB::table('conditions')->insert([
            'name' => 'Obesity',
            'description_en' => 'Obesity is a complex disease involving an excessive amount of body fat. Obesity isn\'t just a cosmetic concern. It is a medical problem that increases your risk of other diseases and health problems, such as heart disease, diabetes, high blood pressure and certain cancers.',
            'description_sk' => 'Obezita je komplexné ochorenie zahrňujúce nadmerné množstvo tuku v tele. Obezita nie je len kozmetická záležitosť. Je to zdravotný problém, ktorý zvyšuje riziko pre iné ochorenia a zdravotné problémy, ako sú napríklad srdcové ochorenia, cukrovka, vysoký krvný tlak a niektoré druhy rakovín',
        ]);

        DB::table('conditions')->insert([
            'name' => 'Morbid obesity',
            'description_en' => 'Morbid obesity is a condition in which you have a body mass index (BMI) higher than 35. BMI is used to estimate body fat and can help determine if you are at a healthy body weight for your size. BMI is not a perfect measurement but it does help give a general idea of ideal weight ranges for height.',
            'description_sk' => 'Morbídna obezita je stav, kedy je Váš index telesnej hmotnosti (BMI - body mass index) vyšší ako 35. BMI sa používa na odhad telesného tuku a môže pomôcť určiť, či máte zdravú telesnú hmotnosť pre Vašu výšku. BMI nie je perfektným meraním, no pomáha udať všeobecnú ideu ideálneho rozsahu hmotnosti pre konkrétnu výšku.',
        ]);

        DB::table('conditions')->insert([
            'name' => 'Overweight',
            'description_en' => 'Being overweight or fat is having more body fat than is optimally healthy. Being overweight is especially common where food supplies are plentiful and lifestyles are sedentary. A healthy body requires a minimum amount of fat for proper functioning of the hormonal, reproductive, and immune systems, as thermal insulation, as shock absorption for sensitive areas, and as energy for future use; however, the accumulation of too much storage fat can impair movement, flexibility, and alter the appearance of the body.',
            'description_sk' => 'Nadváha znamená mať viac telesného tuku než je optimálne zdravé. Nadváha je častá tam, kde je veľa zásob jedla a životný štýl je sedavý. Zdravé telo potrebuje minimálne množstvo tuku pre správne fungovanie hormonálneho, reproduktívneho a imunitného systému, ako termálnu izoláciu, tlmenie nárazov v citlivých oblastiach a ako energiu pre použitie v budúcnosti. Avšak, akumulácia prílišného odloženého tuku môže narušiť pohyb, flexibilitu a zmeniť výzor tela.',
        ]);

        DB::table('conditions')->insert([
            'name' => 'Kidney stones',
            'description_en' => 'Kidney stones (also called renal calculi, nephrolithiasis or urolithiasis) are hard deposits made of minerals and salts that form inside your kidneys. Diet, excess body weight, some medical conditions, and certain supplements and medications are among the many causes of kidney stones. Kidney stones can affect any part of your urinary tract — from your kidneys to your bladder. Often, stones form when the urine becomes concentrated, allowing minerals to crystallize and stick together.',
            'description_sk' => 'Obličkové kamene (tiež nazývané nefrolitiáza alebo urolitiáza) sú tvrdé ložiská vytvorené z minerálov a solí formujúce sa vo vnútri obličiek. Diéta, nadmerná telesná hmotnosť, niektoré zdravotné stavy a určité doplnky a lieky môžu spôsobovať obličkové kamene. Obličkové kamene môžu ovplyvňovať hociktorú časť Vašich močových ciest - od obličiek až po mechúr. Často sa kamene tvoria keď je moč koncentrovaný, čo dovoľuje minerálom kryštalizovať a držať sa pohromade.',
        ]);

    }
}

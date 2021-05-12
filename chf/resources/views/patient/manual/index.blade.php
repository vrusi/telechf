@extends('layouts.app')

@section('content')
    <div class="container mb-5 pb-5">
        <h1>{{ __('Manual') }}</h1>

        <h2>Vaša terapia</h2>
        <p class="text-justify">
            V časti <a href="{{ route('therapy') }}">{{ __('Therapy') }}</a> si môžete prezrieť
            informácie o vašej terapii. Uvidíte tu, ktoré parametre sú Vám monitorované, aké odporúčania Vám boli dané, na
            čo ste {{ Auth::user()->sex == 'female' ? 'liečená' : 'liečený' }} a ktoré lieky máte predpísané.
        </p>

        <h3>Monitorované parametre</h3>
        <p class="text-justify">
            Monitorované parametre sú také parametre, ktoré Vám boli určené na meranie. Ak máte medzi monitorovanými
            parametrami napríklad systolický a diastolický krvný tlak a tep srdca, tak by ste si v rámci tejto aplikácie
            mali merať tlak a tep srdca.
        </p>
        <p class="text-justify">
            Ku každému parametru budete vidieť Vaše cieľové hodnoty. Napríklad, ak máte ku systolickému krvnému tlaku
            napísanú
            hodnotu „≤ 110 mmHg“, znamená to, že primeranou terapiou sa Váš horný tlak snažíme dostať pod 110, aby sme tak
            pre Vás dosiahli čo najlepšie zdravie. Nemajte však strach - ak Vaše merania presiahnú tieto cieľové hodnoty,
            neznamená to, že máte problém; tieto hodnoty sú orientačné pre Vás, aby ste vedeli, ako je na tom Vaša liečba.
            Ak Vám neboli cieľové hodnoty stanovené, uvidíte v stĺpci „--“.
        </p>
        <p class="text-justify">
            Každý parameter môže mať pridelenú frekvenciu meraní. Napríklad, ak máte ku systolickému krvnému tlaku napísané
            „3-krát za týždeň“, mali by ste si merať tlak trikrát týždenne. Nemerajte si však potom tlak trikrát za deň, ale
            napríklad tri dni po sebe, prípadne si to rozložte tak, ako Vám vyhovuje. Ak Vám nebola určená frekvencia v akej
            máte parameter merať, uvidíte v stĺpci „--“.
        </p>

        <h3>Odporúčania</h3>
        <p class="text-justify">
            V tejto časti uvidíte odporúčania, ktoré Vám boli dané. Môže tu byť napríklad niečo ako „nefajčite“ alebo
            „pravidelne cvičte.“
        </p>

        <h3>Liečba</h3>
        <p class="text-justify">
            Ďalej uvidíte zoznam ochorení, na ktoré ste {{ Auth::user()->sex == 'female' ? 'liečená' : 'liečený' }}. Po
            kliknutí na niektoré z nich sa Vám rozbalí krátky popis.
        </p>

        <h3>Lieky</h3>
        <p class="text-justify">
            V neposlednom rade tu môžete vidieť zoznam liekov, ktoré máte predpísané, množstvo v ktorom ich máte brať a ako
            často.
        </p>

        <h2>
            Zadávanie meraní
        </h2>
        <p class="text-justify">
            Svoje merania krvného tlaku, pulzu a hmotnosti môžete do aplikácie zadať v časti <a
                href="{{ route('measurements.create') }}">{{ __('New measurement') }}.</a>
        </p>
        <p class="text-justify">
            V tejto časti budete vidieť, ktoré merania si ešte máte zmerať, podľa toho, ako Vám bola nastavená frekvencia
            meraní pre ten-ktorý parameter. Teda, ak Vám bolo nakázané merať si hmotnosť, tak každý deň pod
            „{{ __('To take today') }}“ uvidíte „Hmotnosť“.
        </p>
        <p class="text-justify">
            Kliknutím si zvoľte meranie, ktoré chcete zadať a dostanete sa na formulár pre zadanie merania. Ak ste na
            počítači, tak po ľavej strane uvidíte inštrukcie k správnemu odmeraniu daného parametra. Ak ste na mobilnom
            zariadení, tak inštrukcie uvidíte zbalené hneď pod nádpisom a v prípade, že ich chcete prečítať, stačí kliknúť
            na „{{ __('Tap to read instructions') }}“ a inštrukcie sa rozbalia.
        </p>
        <p class="text-justify">
            Ďalej uvidíte políčko pre vpísanie hodnoty Vášho merania. Prečítajte hodnotu z Vašej váhy, tlakomera či oximetra
            a vpíšte ju do daného políčka. Môžete vkladať celé aj desatinné čísla, desatinné miesta môžu byť oddelené
            čiarkou či bodkou.
        </p>
        <p class="text-justify">
            Nasledujú tri polia týkajúce sa Vášho momentálneho stavu. Ohodnoťte tieto polia výberom z
            „{{ __('Very good') }},“ „{{ __('Good') }},“
            „{{ __('Neutral') }},“ „{{ __('Bad') }},“
            „{{ __('Very bad') }}.“ Ak nevyberiete žiadnu, k meraniu sa automaticky pripíše
            „{{ __('Neutral') }}“.
        </p>
        <p class="text-justify">
            Nakoniec uložíte meranie kliknutím na tlačidlo „{{ __('Finish') }}.“ Budete presmerovaní do
            časti <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}.</a> Ak meranie uložiť nechcete, môžete sa
            kliknutím na tlačidlo „{{ __('Cancel') }}“ vrátiť na výber parametra merania.
        </p>

        <h3>Extra merania</h3>
        <p>
            Ak ste už vykonali všetky merania, no cítite sa zle, môžete si zmerať extra meranie. Pri extra meraní zadáte
            hodnoty rovnako ako pri normálnom meraní, no zadáte naviac aj textový popis toho, čo vám je alebo ako sa cítite,
            dôvod extra merania.
        </p>

        <h3>
            Ako namerať EKG
        </h3>
        <p class="text-justify">
            Pre odmeranie EKG použite mobilnú aplikáciu, ktorú ste dostali. Prihláste sa do nej Vašimi prihlasovacími
            údajmi, ktoré sú rovnaké, ako tie, ktorými sa prihlasujete do tejto aplikácie. Prihlasujete sa emailom a heslom,
            ak od Vás aplikácia pýta prihlasovacie meno a heslo, tak prihlasovacie meno je vlastne email, ktorým sa
            prihlasujete sem. Nasaďte si EKG elektródy a spusťte meranie. Po ukončení merania kliknite na odoslanie dát, čím
            sa meranie dostane do tejto aplikácie.
        </p>

        <h2>
            Zobrazenie meraní
        </h2>
        <p class="text-justify">
            V časti <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a> máte k dispozícii dve tabuľky, a to
            „{{ __('Alarms') }}“ a „{{ __('Summary') }}“. V alarmoch uvidíte všetky dni,
            kde aspoň jedno meranie prevŕšilo Vaše cielové hodnoty; takéto merania sú vyznačené červenonu farbou. V prípade,
            že ste si v ten deň nenamerali všetky parametre (nemusíte každý deň merať všetko, ak Vám to nebolo určené), tak
            hodnoty tých nenameraných budete vidieť ako „--“.
        </p>
        <p class="text-justify">
            Na konci riadka vidíte aj Vašu priemernú odpoveď k Vášmu stavu za ten deň. Napríklad, ak ste pri jednom meraní
            zvolili, že Vaše opuchy sú veľmi zlé, no neskôr pri ďalšom meraní v ten deň ste zvolili stredne, tak by ste v
            stĺpci „{{ __('Swellings') }}“ mali vidieť Zlé.
        </p>
        <p class="text-justify">
            V tabuľke „{{ __('Summary') }}“ uvidíte všetky Vaše doterajšie dni s meraniami, vrátane tých,
            kde žiadne meranie neprevŕšilo Vaše cieľové hodnoty. Okrem toho sú tieto dve tabuľky rovnaké.
        </p>

        <h2>Grafy</h2>
        <p class="text-justify">
            V časti <a href="{{ route('charts') }}">{{ __('Charts') }}</a> si môžete prezrieť všetky Vaše merania
            vykreslené v grafoch. V hornej časti obrazovky si môžete zvoliť filter, za akú dobu chcete zobraziť dáta.
            Nasledujú grafy pre všetky parametre ktoré sú Vám monitorované. V každom z týchto grafov vidíte Vaše hodnoty v
            čase spolu s Vašou cieľovou hranicou. Neberte túto hranicu príliš striktne - pokiaľ sa Vaše hodnoty pohybujú
            okolo nej, všetko je v poriadku. Ak ešte nemáte nameraných dostatok hodnôt, tieto grafy sa Vám nezobrazia.
        </p>

        <p class="text-justify">
            Za týmito grafmi nasleduje stĺpcový graf o vašom stave,
            v ktorom môžete vidieť, koľkokrát ste vo zvolenom dni vybrali ktorú možnosť („{{ __('Very good') }}“ až
            „{{ __('Very bad') }}“). Deň si môžete zvoliť priamo nad grafom v pravom rohu.
        </p>

        <p class="text-justify">
            Ako posledné uvidíte Vaše EKG. Môžete si zvoliť, ktoré EKG chcete vidieť, podľa dátumu a času merania. V grafe
            sa viete posúvať aby ste videli dlhší priebeh, no naraz sa zobrazuje najviac len zhruba minúta merania. Ak
            chcete vidieť ďalší priebeh, kliknite na šípky označené "Segment" pod výberom EKG.
        </p>


        <h2>Profil</h2>
        <p class="text-justify">
            V časti <a href="{{ route('profile') }}">{{ __('Profile') }}</a> uvidíte Vaše osobné údaje, ktoré o Vás
            máme.
        </p>

        <h2>Kontakty</h2>
        <p class="text-justify">
            V časti <a href="{{ route('contacts.index') }}">{{ __('Contacts') }}</a> si môžete pozrieť kontaktné údaje
            na Vašich lekárov, napríklad na všeobecného lekára či kardiológa.
        </p>
    </div>
@endsection

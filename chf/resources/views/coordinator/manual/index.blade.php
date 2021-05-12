@extends('layouts.app')

@section('content')
    <div class="container mb-5 pb-5">
        <h1>{{ __('Manual') }}</h1>

        <h2>Dashboard</h2>
        <p class="text-justify">
            V časti <a href="{{ route('coordinator.dashboard') }}">{{ __('Dashboard') }}</a> môžete vidieť dve tabuľky
            dostupné cez karty „{{ __('Alarms') }}“ a „{{ __('Extra measurements') }}“. V alarmoch uvidíte všetky dni
            všetkých Vašich pacientov, kde aspoň jedno meranie prevŕšilo limity - či už bezpečnostné alebo terapeutické.
            Bezpečnostné alarmy sú vyznačené červenonu farbou, terapeutické merania sú vyznačené žltou. Okrem farby budete
            pri alarmoch vidieť text znázorňujúci, či hodnota prekročila limit tým, že bola príliš vysoká, alebo či bola
            príliš nízka. Taktiež, pri každej hodnote budete vidieť čas, kedy v daný deň bola hodnota nameraná.
        </p>

        <p class="text-justify">
            Na konci riadka vidíte pacientovu priemernú odpoveď k jeho stavu za ten deň. Napríklad, ak pri jednom meraní
            zvolil, že jeho opuchy sú veľmi zlé, no neskôr pri ďalšom meraní v ten deň zvolil stredne, tak by ste v
            stĺpci „{{ __('Swellings') }}“ mali vidieť Zlé.
        </p>

        <p class="text-justify">
            Pacienti vidia svoje merania tiež, no všetky alarmy vidia v červenej farbe a aplikácia im vraví, že prekročili
            svoje „cieľové
            hodnoty“.
        </p>

        <p class="text-justify">
            Ak si pacient v daný deň nenameral všetky parametre (nemusí, záleží od frekvencie meraní, akú má nastavenú),
            prípadne mu nebol
            priradený niektorý z parametrov na monitorovanie, tak v danom stĺpci bude namiesto
            hodnoty „--“.
        </p>

        <p class="text-justify">
            Tabuľka „{{ __('Extra measurements') }}“ je rovnaká ako tabuľka „{{ __('Alarms') }}“ až na to, že ukazuje
            len tie merania, ktoré pacient vykonal nad rámec frekvencií meraní, ktoré mu boli nastavené. Pri každom meraní
            by mal byť nejaký popis od pacienta, vysvetlenie toho, prečo vykonal extra meranie. Tento popis sa Vám zobrazí
            po kliknutí na tlačidlo „{{ __('Show description') }}.“
        </p>

        <h2>Globálne limity</h2>
        <p class="text-justify">
            V časti <a href="{{ route('coordinator.thresholds') }}">{{ __('Thresholds') }}</a> si môžete prezrieť
            nastavené globálne bezpečnostné limity. Ku každému parametru ktorý sa v našej aplikácií dá monitorovať budete
            vidieť globálne bezpečnostné hodnoty, ktoré platia pre každého pacienta, pokiaľ nemá nastavené osobné
            bezpečnostné hodnoty. Ak globálny bezpečnostný limit nebol nastavený, uvidíte v stĺpci „--“.
        </p>

        <p class="text-justify">
            Každý parameter môže mať pridelenú frekvenciu meraní. Napríklad, ak je pre systolický krvný tlak napísané
            „3-krát za týždeň“, pacienti s týmto parametrom by si mali merať tlak trikrát týždenne. Ak frekvencia nebola
            nastavená, uvidíte v stĺpci „--“.
        </p>

        <h3 id="global-thresholds-edit">Upravovanie limitov</h3>
        <p class="text-justify">
            Tieto globálne limity sa dajú upraviť kliknutím na tlačidlo „{{ __('Edit global thresholds') }}“. Zobrazí sa
            Vám formulár na úpravu limitov, kde môžete buď vkladať nové hodnoty, ponechať staré (nevyplnením políčka),
            prípadne ich zmazať označením políčka „{{ __('Remove upper threshold') }}“ resp.
            „{{ __('Remove lower threshold') }}“ pre konkrétny parameter.
        </p>
        <p class="text-justify">
            Rovnakým spôsobom sa v pravej časti stránky upravujú frekvencie meraní. No namiesto limitov
            sa vpíše číslo značiace koľkokrát sa majú pacienti merať a doba, za ktorú tak majú urobiť. Napríklad vpísanie
            čísla 3 a voľba
            hodnoty „{{ __('week') }}“ značí, že si pacienti majú daný parameter merať trikrát za týždeň.
        </p>

        <p> Nastavovanie osobných limitov opisujeme <a href="#personal-limits">nižšie.</a></p>

        <h2>Pacienti</h2>
        <p class="text-justify">
            V časti <a href="{{ route('patients.index') }}">{{ __('Patients') }}</a> môžete vidieť zoznam všetkých
            Vašich pacientov. Kliknutím na červené tlačidlo v stĺpci „{{ __('Deactivate') }}“ nastavíte pacienta ako
            neaktívneho a presunie sa do tabuľky „{{ __('Inactive patients') }}.“ Po kliknutí na šípku v stĺpci
            „{{ __('Detail') }}“ prejdete na <a href="#profile">pacientov profil.</a>
        </p>

        <h3>Vytvorenie nového pacienta</h3>
        <p class="text-justify">
            Po kliknutí na „{{ __('New patient') }}“ v pravom hornom rohu môžete vytvoriť profil novému pacientovi.
            Povinné polia sú tu len email a heslo, všetko ostatné sa dá zmeniť/prideliť neskôr z pacientovho profilu.
        </p>
        <p class="text-justify">
            Dôležité polia sú najmä „{{ __("The MAC address of patient's ECG sensor") }},“ vďaka ktorej si pacient bude
            môcť merať EKG. Ďalej bude potrebné pacientovi zakliknúť monitorované parametre a ich frekvencie meraní,
            nech k nim vie zadávať svoje hodnoty meraní.
        </p>

        <p class="text-justify">
            Limity, ktoré zadáte parametrom pri vytváraní pacientovho profilu sú jeho osobné limity. Ak žiadne limity
            nezadáte, tak preňho budú platiť globálne bezpečnostné limity.
        </p>

        <h3 id="profile">Profil pacienta</h3>
        <p class="text-justify">
            V tejto časti si viete prezrieť a upraviť pacientove osobné údaje. „{{ __('External patient ID') }}“ a
            „{{ __('External doctor ID') }}“ slúžia na identifikáciu pacienta v externej aplikácií, vďaka ktorej si
            pacient môže merať EKG. Tieto hodnoty pravdepodobne nikdy nebude treba meniť, iba ak by nastal akýsi problém.
        </p>

        <h4 id="personal-limits">Terapia</h4>
        <p class="text-justify">
            V tejto časti nájdete parametre, ktoré sú pacientovi monitorované, odporúčania k jeho liečbe, zoznam ochorení,
            na ktoré sa lieči a lieky, ktoré má predpísané.
        </p>

        <p class="text-justify">
            Ak pacient nemá nastavené žiadne osobné limity, len globálne, tak v
            Úprava osobných limitov pre pacienta funguje rovnako, ako <a href="#global-thresholds-edit">úprava globálnych
                limitov,</a> no v tejto časti sa okrem bezpečnostných limitov môžu upravovať aj terapeutické limity.
        </p>

        <h4>Merania</h4>
        <p class="text-justify">
            V tejto časti môžete vidieť tabuľku všetkých pacientových meraní, vrátane dní, kedy nevznikol žiaden alarm. Na
            vrchu stránky vidíte všetky alarmy, ktoré boli spustené pacientovými meraniami ako aj zdroj alarmu. Ak ste alarm
            skontrolovali, kontaktovali pacienta, upravili liečbu, prípadne usúdili, že s alarmom nie je potrebné nič robiť,
            môžete ho označiť za skontrolovaný.
        </p>
        <p class="text-justify">
            Pod alarmami sú tlačidlá pre kontakt na pacienta a jeho lekárov. Po kliknutí na tieto tlačidlá na obrazovku
            vyskočí okienko zobrazujúce daný kontakt (meno, email a/alebo telefónne číslo).
        </p>

        <h4>Grafy</h4>
        <p class="text-justify">
            V časti Grafy si môžete prezrieť všetky pacientove merania vykreslené v grafoch. V hornej časti obrazovky si
            môžete zvoliť filter, za akú dobu chcete zobraziť dáta. Nasledujú grafy pre všetky parametre ktoré sú pacientovi
            monitorované. V každom z týchto grafov vidíte pacientove hodnoty v čase spolu s jeho limitmi. Pacient v grafoch
            vidí len terapeutický limit pod názvom „Cieľová hodnota“. Ak pacient ešte nemá nameraných dostatok hodnôt, tieto
            grafy sa Vám nezobrazia.
        </p>

        <p class="text-justify">
            Za týmito grafmi nasleduje stĺpcový graf o pacientovom stave, v ktorom môžete vidieť, koľkokrát vo zvolenom dni
            vybral ktorú možnosť („{{ __('Very good') }}“ až „{{ __('Very bad') }}“). Deň si môžete zvoliť priamo nad
            grafom v pravom rohu.
        </p>

        <p class="text-justify">
            Ako posledné uvidíte pacientove EKG. Môžete si zvoliť, ktoré EKG chcete vidieť, podľa dátumu a času merania. V
            grafe sa viete posúvať aby ste videli dlhší priebeh, no naraz sa zobrazuje najviac len zhruba minúta merania. Ak
            chcete vidieť ďalší priebeh, kliknite na šípky označené „{{ __('Segment') }}“ pod výberom EKG.
        </p>

        <p class="text-justify">
            Ako koordinátor vidíte, ak EKG senzor zistil výpadok EKG zariadenia, bradykardiu, tachykardiu či fibriláciu
            predsiení. V legende sa zobrazuje číslo, koľkokrát bola daná udalosť nameraná. Ak je tam „(0)“, tak senzor
            nezistil počas merania udalosť ani raz, inak ju zistil a uvidíte ju v grafe farebne vyznačenú v čase, kedy ju
            zistil. Ak ju nevidíte na prvý pohľad,
            budete si musieť oddialiť graf alebo sa po ňom poposúvať, aby ste videli, kde tá udalosť bola nameraná.
        </p>

        <p class="text-justify">
            Môžete tiež kliknúť do EKG aby ste doň vložili značku. Každé dva po sebe idúce značky vám vyrátajú časový
            rozdiel medzi nimi, ktorý uvidíte nad grafom. Takéto rozdiely si môžete nechať napočítať tri, teda použiť šesť
            značiek. Ak by ste potrebovali použiť ďalšie značky, budete musieť znovu načítať stránku. Toto počítanie
            rozdielov slúži na to, aby ste vedeli napríklad namerať dĺžky komplexov.
        </p>

        <h4>Kontakty</h4>
        <p class="text-justify">
            V časti {{ __('Contacts') }} si môžete pozrieť kontaktné údaje na pacientových lekárov, napríklad na jeho
            všeobecného lekára či kardiológa.
        </p>
    </div>
@endsection

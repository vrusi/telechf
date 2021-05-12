@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('Manual') }}</h1>

        <h2>
            Zadávanie meraní
        </h2>
        <p>
            Svoje merania <strong>krvného tlaku,</strong> <strong>pulzu</strong> a <strong>hmotnosti</strong> môžete do
            aplikácie zadať v časti <a href="{{ route('measurements.create') }}">{{ __('New measurement') }}.</a>
        </p>
        <p>
            V tejto časti budete vidieť, ktoré merania si ešte máte zmerať, podľa toho, ako Vám bola nastavená frekvencia
            meraní pre ten-ktorý parameter. Teda, ak Vám bolo nakázané merať si hmotnosť, tak každý deň pod
            {{ __('To take today') }} uvidíte Hmotnosť.
        </p>
        <p>
            Kliknutím si zvoľte meranie, ktoré chcete zadať a dostanete sa na formulár pre zadanie merania. Ak ste na
            počítači, tak po ľavej strane uvidíte inštrukcie k správnemu odmeraniu daného parametra. Ak ste na mobilnom
            zariadení, tak inštrukcie uvidíte zbalené hneď pod nádpisom a v prípade, že ich chcete prečítať, stačí kliknúť
            na {{ __('Tap to read instructions') }} a inštrukcie sa rozbalia.
        </p>
    </div>
@endsection

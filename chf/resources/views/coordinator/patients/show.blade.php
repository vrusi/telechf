@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Patients</h1>

    <h2> {{ $patient['name'].' '.$patient['surname'] }}</h2>

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="{{'/coordinator/patients/'.$patient['id'].'/measurements'}}">Measurements</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{'/coordinator/patients/'.$patient['id'].'/charts'}}">Charts</a>
        </li>
    </ul>
</div>
@endsection

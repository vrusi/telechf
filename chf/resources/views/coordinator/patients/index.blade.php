@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Patients</h1>

    <div>
        @foreach($patients as $patient)
        <p>
            <a href="{{ 'patients/'.$patient->id }}">
                {{ $patient['name'].' '.$patient['surname'] }}
            </a>
        </p>
        @endforeach

    </div>
</div>
@endsection

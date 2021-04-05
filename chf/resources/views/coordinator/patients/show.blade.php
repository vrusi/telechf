@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Patients</h1>

   <h2> {{ $patient['name'].' '.$patient['surname'] }}</h2>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    @if(!empty($takeToday))
    <div>
        <h3>
            To take today
        </h3>
        @foreach($takeToday as $parameter)
        <div class="my-3">
            <a class="btn btn-outline-primary" href="{{ url('/measurements/create/'.$parameter->id) }}">
                {{ $parameter->name }}
            </a>
        </div>

        @endforeach
    </div>
    @endif

    @if(!empty($takeThisWeek))
    <div>
        <h3>
            To take this week
        </h3>
        @foreach($takeThisWeek as $parameter)
        <div class="my-3">
            <a class="btn btn-outline-primary" href="{{ url('/measurements/create/'.$parameter->id) }}">
                {{ $parameter->name }}
            </a>
        </div>

        @endforeach
    </div>
    @endif

    @if(!empty($extra))
    <div>
        <h3>
            Extra measurements
        </h3>
        @foreach($extra as $parameter)
        <div class="my-3">
            <a class="btn btn-outline-primary" href="{{ url('/measurements/create/'.$parameter['id']) }}">
                {{ $parameter['name'] }}
            </a>
        </div>

        @endforeach
    </div>
    @endif

    <a class="btn btn-primary mt-3" href="{{ url('/measurements') }}">
        Cancel
    </a>

</div>
@endsection

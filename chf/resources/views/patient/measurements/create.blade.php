@extends('layouts.app')

@section('content')
<div class="container patient">
    <div class="row">
        <div class="col pb-3">
            <h1>
                New measurement
            </h1>

            <span>
                Please pick which measurement you would like to take and record.
            </span>

        </div>
    </div>
    <div class="row">
        <div class="col">
            <h2>
                To take today
            </h2>
            @if(!empty($takeToday))
            @foreach($takeToday as $parameter)
            <div class="my-3">
                <a class="btn btn-outline-primary w-100 text-left" href="{{ url('/measurements/create/'.$parameter->id) }}">
                    {{ $parameter->name }}
                </a>
            </div>

            @endforeach
        </div>
    </div>
    @else
    <div class="my-3">
        <p>
            You have taken all your daily measurements.
        </p>
    </div>
    @endif


    @if(!empty($takeThisWeek))
    <div class="row">
        <div class="col">
            <h2>
                To take this week
            </h2>

            @foreach($takeThisWeek as $parameter)
            <div class="my-3">
                <a class="btn btn-outline-primary w-100 text-left" href="{{ url('/measurements/create/'.$parameter->id) }}">
                    {{ $parameter->name }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($extra))
    <div class="row">
        <div class="col">
            <h2>
                Extra measurements
            </h2>
            @foreach($extra as $parameter)
            <div class="my-3">
                <a class="btn btn-outline-primary w-100 text-left" href="{{ url('/measurements/create/'.$parameter['id']) }}">
                    {{ $parameter['name'] }}
                </a>
            </div>
            @endforeach

        </div>
    </div>
    @endif

    <div class="row pt-5">
        <div class="col">
            {{-- sticky on mobile --}}
            <div class="d-md-none fixed-bottom">
                <a class="btn btn-secondary w-100 rounded-0" href="{{ url('/measurements') }}" role="button">
                    Cancel
                </a>
            </div>

            {{-- button on tablets and desktop --}}
            <div class="d-none d-md-block">
                <div class="d-flex justify-content-center">
                    <a class="btn btn-secondary w-100" href="{{ url('/measurements') }}" role="button">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

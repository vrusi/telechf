@extends('layouts.app')

@section('content')
<div class="container patient">
    <div class="row">
        <div class="col pb-3">
            <h1>
                Your measurements
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="pb-5 d-flex align-items-center justify-content-center">
                @if($previous)
                <a href="{{ url($previous) }}">
                    <button type="button" class="btn btn-outline-primary">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </a>
                @else
                <button type="button" class="btn btn-outline-primary" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                @endif

                <div class="mx-5">
                    {{ date('F d', strtotime($date)) }}
                </div>

                @if($next)
                <a href="{{ url($next) }}">
                    <button type="button" class="btn btn-outline-primary">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </a>
                @else
                <button type="button" class="btn btn-outline-primary" disabled>
                    <i class="fas fa-chevron-right"></i>
                </button>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="pb-5 d-flex justify-content-center">
                <table>
                    <thead>
                        <tr>
                            <th>
                                Parameter
                            </th>
                            <th class="px-3">
                                Value
                            </th>
                            <th>
                                Time
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- measurements --}}
                        @foreach($measurements as $measurement)
                        <tr>
                            <td>{{ $measurement['parameter'] }}</td>
                            <td class="px-3">{{ $measurement['value'] ?? '--' }} {{ $measurement['value'] && $measurement['unit'] ? $measurement['unit'] : '' }}</td>
                            <td>{{ date('H:i', strtotime($measurement['date'])) ?? '--' }}</td>
                        </tr>
                        @endforeach

                        {{-- conditions --}}
                        @foreach($conditions as $condition)
                        <tr>
                            <td>{{ $condition['name'] }}</td>
                            <td class="px-3">{{ $condition['value'] ?? '--' }} </td>
                            <td></td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col">
            {{-- sticky on mobile --}}
            <div class="d-md-none fixed-bottom">
                <a class="btn btn-primary w-100 rounded-0" href="{{ url('/measurements/create') }}" role="button">
                    New measurement
                </a>
            </div>

            {{-- button on tablets and desktop --}}
            <div class="d-none d-md-block">
                <div class="d-flex justify-content-center">
                    <a class="btn btn-primary w-50" href="{{ url('/measurements/create') }}" role="button">
                        New measurement
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

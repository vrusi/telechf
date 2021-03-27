@extends('layouts.app')

@section('content')
<div class="container">
    <div class="pb-5">
        @if($previous)
        <a class="btn btn-outline-primary" href="{{ url($previous) }}">
            Previous
        </a>
        @endif

        @if($next)
        <a class="btn btn-outline-primary" href="{{ url($next) }}">
            Next
        </a>
        @endif
    </div>

    <div class="pb-5">

        <table>
            <tbody>
                @foreach($measurements as $measurement)
                <tr>
                    <td class="font-weight-bold">{{ $measurement['parameter'] }}</td>
                    <td>{{ $measurement['value'] ?? '--' }} {{ $measurement['value'] && $measurement['unit'] ? $measurement['unit'] : '' }}</td>
                </tr>
                @endforeach
                @foreach($conditions as $condition)
                <tr>
                    <td class="font-weight-bold">{{ $condition['name'] }}</td>
                    <td>{{ $condition['value'] ?? '--' }} </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a class="btn btn-primary" href="{{ url('/measurements/create') }}" role="button">
        New measurement
    </a>

</div>
@endsection

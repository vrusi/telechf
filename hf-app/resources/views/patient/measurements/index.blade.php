@extends('layouts.app')

@section('content')
<div class="container">
    <div class="py-12">
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

        <table>
            <tbody>
                @foreach($measurements as $measurement)
                <tr>
                    <td><strong>{{ $measurement['parameter']}}</strong></td>
                    <td>{{ $measurement['value'] ?? '--'}} {{$measurement['unit'] ?? '--'}}</td>
                </tr>
                @endforeach
                @foreach($conditions as $condition)
                <tr>
                    <td><strong>{{ $condition['name']}}</strong></td>
                    <td>{{ $condition['value']}} </td>
                </tr>
                @endforeach
            </tbody>
        </table>


        <a class="btn btn-primary" href="{{ url('/measurements/create') }}" role="button">
            New measurement
        </a>

    </div>

</div>
@endsection

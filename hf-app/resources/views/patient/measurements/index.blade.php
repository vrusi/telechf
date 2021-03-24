@extends('layouts.app')

@section('content')
<div class="container">
    <div class="py-12">
        @if($previous)
        <a href="{{ url($previous) }}">
            Previous
        </a>
        @endif

        @if($next)
        <a href="{{ url($next) }}">
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

        <div class="p-6 bg-white border-b border-gray-200">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <a href="{{ url('/measurements/create') }}">
                    New measurement
                </a>
            </button>
        </div>
    </div>

</div>
@endsection

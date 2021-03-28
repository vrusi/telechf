@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div class="container">

    <h1>
        Your therapy
    </h1>

    <h2>
        Monitored parameters
    </h2>

    <table>
        <thead>
            <tr>
                <th>Parameter</th>
                {{--
                    TODO:
                    <td>
                        Goal values
                    </td>
                --}}
                <th>
                    Measurement frequency
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($parameters as $parameter)
            <tr>
                <td class="pr-3">
                    {{ $parameter->name }}
                </td>

                {{-- TODO:
                 <td>

                </td>
                 --}}

                <td>
                    @if($parameter->measurement_times)
                    @if($parameter->measurement_times == 1)
                    {{ 'once per '.$parameter->measurement_span }}
                    @endif
                    @if($parameter->measurement_times == 2)
                    {{ 'twice per '.$parameter->measurement_span }}
                    @endif
                    @if($parameter->measurement_times >= 3)
                    {{ $parameter->measurement_times.' times per '.$parameter->measurement_span }}
                    @endif
                    @endif

                    @if(!$parameter->measurement_times)
                    {{ '--' }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

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


    <h2>
        Recommendations
    </h2>
    <div>
        TODO
    </div>

    <h2>
        You are being treated for
    </h2>
    @foreach($conditions as $condition)
    <div>
        {{ $condition->name }}
    </div>
    @endforeach

    <h2>
        You are currently prescribed
    </h2>
    <table>
        @foreach($drugs as $drug)
        <tr>
            <td class="font-weight-bold pr-3">
                {{ $drug->name }}

            </td>

            <td class="pr-3">
                {{ $drug->dosage_volume.' '.$drug->dosage_unit }}
            </td>

            <td class="pr-3">
                @if($drug->dosage_times)
                @if($drug->dosage_times == 1)
                {{ 'once per '.$drug->dosage_span }}
                @endif
                @if($drug->dosage_times == 2)
                {{ 'twice per '.$drug->dosage_span }}
                @endif
                @if($drug->dosage_times >= 3)
                {{ $drug->dosage_times.' times per '.$drug->dosage_span }}
                @endif
                @endif

                @if(!$drug->dosage_times)
                {{ '--' }}
                @endif
            </td>
        </tr>

        @endforeach
    </table>

</div>
@endsection

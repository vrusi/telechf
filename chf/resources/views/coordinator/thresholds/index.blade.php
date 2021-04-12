@extends('layouts.app')

@section('content')

<div class="container">
    <h1>
        Global thresholds
    </h1>
    <p>
        These threshold settings apply to every patient by default unless personal thresholds have been set for a patient. You can set personal thresholds for specific patients by visiting the thresholds tab within their profile.
    </p>


    <table>
        <thead>
            <tr>
                <th class="pr-3">
                    Parameter
                </th>

                <th class="pr-3">
                    Safety threshold
                </th>

                <th>
                    Measurement frequency
                </th>
            </tr>

        </thead>
        <tbody>

            @foreach($parameters as $parameter)
            <tr>
                <td class="pr-3">
                    {{ $parameter['name'] }}
                </td>

                <td class="pr-3">
                    {{-- both min and max --}}
                    @if($parameter->threshold_min && $parameter->threshold_max)
                    {{ $parameter->threshold_min }} - {{ $parameter->threshold_max }} {{ $parameter->unit }}

                    {{-- only min --}}
                    @elseif($parameter->threshold_min && !$parameter->threshold_max)
                    ≥ {{ $parameter->threshold_min }} {{ $parameter->unit }}

                    {{-- only max --}}
                    @elseif(!$parameter->threshold_min && $parameter->threshold_max)
                    ≤ {{ $parameter->threshold_max }} {{ $parameter->unit }}

                    {{-- neither --}}
                    @else
                    --
                    @endif
                </td>

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

    <a class="btn btn-primary" href="{{ route('coordinator.thresholds.create') }}">
        Edit global thresholds
    </a>
</div>
@endsection

@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1>
                Global thresholds
            </h1>
        </div>
        <div>
            <a class="btn btn-outline-secondary" href="{{ route('coordinator.thresholds.create') }}">
                <i class="fas fa-edit"></i>
                Edit global thresholds
            </a>
        </div>
    </div>
    <p class="text-justify">
        These threshold settings apply to every patient by default unless personal thresholds have been set for a patient. You can set personal thresholds for specific patients by visiting the thresholds tab within their profile.
    </p>

    <div class="d-flex justify-content-center">
        <table class="my-4">
            <thead>
                <tr>
                    <th>
                        Parameter
                    </th>

                    <th class="px-5">
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
                    <td>
                        {{ $parameter['name'] }}
                    </td>

                    <td class="px-5">
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
    </div>
</div>
@endsection

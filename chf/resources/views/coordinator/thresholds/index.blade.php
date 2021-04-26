@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1>
                {{ __('Global thresholds') }}
            </h1>
        </div>
        <div>
            <a class="btn btn-outline-secondary" href="{{ route('coordinator.thresholds.create') }}">
                <i class="fas fa-edit"></i>
                {{ __('Edit global thresholds') }}
            </a>
        </div>
    </div>
    <p class="text-justify">
        {{ __('These threshold settings apply to every patient by default unless personal thresholds have been set for a patient. You can set personal thresholds for specific patients by visiting the thresholds tab within their profile.') }}
    </p>

    <div class="d-flex justify-content-center">
        <table class="my-4">
            <thead>
                <tr>
                    <th>
                        {{ __('Parameter') }}
                    </th>

                    <th class="px-5">
                        {{ __('Safety threshold') }}
                    </th>

                    <th>
                        {{ __('Measurement frequency') }}
                    </th>
                </tr>

            </thead>
            <tbody>

                @foreach($parameters as $parameter)
                <tr>
                    <td>
                        {{ __($parameter['name']) }}
                    </td>

                    <td class="px-5">
                        @if(strtolower($parameter['name']) != 'ecg')
                        {{-- both min and max --}}
                        @if($parameter->threshold_min && $parameter->threshold_max)
                        {{ $parameter->threshold_min }} - {{ $parameter->threshold_max }} {{ __($parameter->unit) }}

                        {{-- only min --}}
                        @elseif($parameter->threshold_min && !$parameter->threshold_max)
                        ≥ {{ $parameter->threshold_min }} {{ __($parameter->unit) }}

                        {{-- only max --}}
                        @elseif(!$parameter->threshold_min && $parameter->threshold_max)
                        ≤ {{ $parameter->threshold_max }} {{ __($parameter->unit) }}

                        {{-- neither --}}
                        @else
                        --
                        @endif
                        @endif
                    </td>

                    <td>
                        @if($parameter->measurement_times)
                        @if($parameter->measurement_times == 1)
                        {{ __('once per').' '.__($parameter->measurement_span) }}
                        @endif
                        @if($parameter->measurement_times == 2)
                        {{ __('twice per').' '.__($parameter->measurement_span) }}
                        @endif
                        @if($parameter->measurement_times >= 3)
                        {{ $parameter->measurement_times.__('times per').' '.__($parameter->measurement_span) }}
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

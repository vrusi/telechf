@extends('layouts.app')

@section('content')
<div class="container">
    <h1>
        {{ __('Patients') }}
    </h1>

    <h3>
        {{ $patient['name'].' '.$patient['surname'] }}
    </h3>

    <ul class="nav nav-tabs my-4">
        <li class="nav-item">
            <a class="{{ Request::is('*/profile*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/profile'}}">{{ __('Profile') }}</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/therapy*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/therapy'}}">{{ __('Therapy') }}</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/measurements*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/measurements'}}">{{ __('Measurements') }}</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/charts*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/charts'}}">{{ __('Charts') }}</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/contacts*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/contacts'}}">{{ __('Contact') }}</a>
        </li>
    </ul>

    <div class="my-3">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3>
                    {{ __('Monitored parameters') }}
                </h3>
            </div>
            <div>
                <a href="{{'/coordinator/patients/'.$patient['id'].'/therapy/thresholds/create'}}" class="btn btn-outline-secondary">
                    <i class="fas fa-edit"></i>
                    {{ __('Edit personal thresholds') }}
                </a>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <table class="w-75">
                <thead>
                    <tr>
                        <th>
                            {{ __('Parameter') }}
                        </th>
                        <th class="px-3">
                            {{ __('Safety threshold') }}
                        </th>
                        <th class="pr-3">
                            {{ __('Therapeutic threshold') }}
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
                            {{ __($parameter->name) }}
                        </td>

                        {{-- safety --}}
                        <td class="px-3">
                            @if(strtolower($parameter['name']) != 'ecg')

                            {{-- both min and max --}}
                            @if($parameter->pivot->threshold_safety_min && $parameter->pivot->threshold_safety_max)
                            {{ $parameter->pivot->threshold_safety_min }} - {{ $parameter->pivot->threshold_safety_max }} {{ __($parameter->unit) }}

                            {{-- only min --}}
                            @elseif($parameter->pivot->threshold_safety_min && !$parameter->pivot->threshold_safety_max)
                            ≥ {{ $parameter->pivot->threshold_safety_min }} {{ __($parameter->unit) }}

                            {{-- only max --}}
                            @elseif(!$parameter->pivot->threshold_safety_min && $parameter->pivot->threshold_safety_max)
                            ≤ {{ $parameter->pivot->threshold_safety_max }} {{ __($parameter->unit) }}

                            {{-- neither --}}
                            @else
                            --
                            @endif
                            @endif
                        </td>


                        {{-- therapeutic --}}
                        <td class="pr-3">
                            @if(strtolower($parameter['name']) != 'ecg')

                            {{-- both min and max --}}
                            @if($parameter->pivot->threshold_therapeutic_min && $parameter->pivot->threshold_therapeutic_max)
                            {{ $parameter->pivot->threshold_therapeutic_min }} - {{ $parameter->pivot->threshold_therapeutic_max }} {{ __($parameter->unit) }}

                            {{-- only min --}}
                            @elseif($parameter->pivot->threshold_therapeutic_min && !$parameter->pivot->threshold_therapeutic_max)
                            ≥ {{ $parameter->pivot->threshold_therapeutic_min }} {{ __($parameter->unit) }}

                            {{-- only max --}}
                            @elseif(!$parameter->pivot->threshold_therapeutic_min && $parameter->pivot->threshold_therapeutic_max)
                            ≤ {{ $parameter->pivot->threshold_therapeutic_max }} {{ __($parameter->unit) }}

                            {{-- neither --}}
                            @else
                            --
                            @endif
                            @endif
                        </td>

                        <td>
                            @if($parameter->pivot->measurement_times)
                            @if($parameter->pivot->measurement_times == 1)
                            {{ __('once per').' '.__($parameter->pivot->measurement_span) }}
                            @endif
                            @if($parameter->pivot->measurement_times == 2)
                            {{ __('twice per').' '.__($parameter->pivot->measurement_span) }}
                            @endif
                            @if($parameter->pivot->measurement_times >= 3)
                            {{ $parameter->pivot->measurement_times.__('times per').' '.__($parameter->pivot->measurement_span) }}
                            @endif
                            @endif

                            @if(!$parameter->pivot->measurement_times)
                            {{ '--' }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <div class="my-3">
        <h3>
            {{ __('Recommendations') }}
        </h3>
        @if($patient->recommendations)
        <div>
            {!! $patient->recommendations !!}
        </div>
        @else
        <div>
            {{ __('There are no recommendations for') }} {{ $patient['name'] }}.
        </div>
        @endif
    </div>

    <div class="my-3">
        <h3>
            {{ $patient['name'] }} {{ __('is being treated for') }}
        </h3>

        @if(count($conditions))
        @foreach($conditions as $condition)
        <div x-data="{ descriptionOpen: false }">

            <div @click="descriptionOpen=!descriptionOpen" class="d-flex align-items-center" data-toggle="collapse" data-target="{{'#conditionDescription'.$condition->id}}" aria-expanded="false" aria-controls="conditionDescription">
                <div class="mr-3">
                    {{ ucfirst(trans($condition->name)) }}
                </div>

                <i x-show="!descriptionOpen" class="fas fa-caret-down"></i>
                <i x-show="descriptionOpen" class="fas fa-caret-up"></i>

            </div>
        </div>

        <div class="collapse" id="{{'conditionDescription'.$condition->id}}">
            <div class="card card-body">
                @if ($locale == 'en')
                {!! $condition->description_en !!}
                @elseif ($locale == 'sk')
                {!! $condition->description_sk !!}
                @endif
            </div>
        </div>
        @endforeach
        @else
        <div>
            {{ $patient['name'] }} {{ __('has no conditions.') }}
        </div>
        @endif

    </div>
    <div class="my-3">
        <h3>
            {{ $patient['name'] }} {{ __('is currently prescribed') }}
        </h3>

        @if(count($drugs) > 0)
        <table>
            @foreach($drugs as $drug)
            <tr>
                <td class="font-weight-bold pr-3">
                    {{ ucfirst(trans($drug->name)) }}
                </td>

                <td class="pr-3">
                    {{ $drug->dosage_volume.' '.$drug->dosage_unit }}
                </td>

                <td class="pr-3">
                    @if($drug->dosage_times)
                    @if($drug->dosage_times == 1)
                    {{ __('once per').' '.__($drug->dosage_span) }}
                    @endif
                    @if($drug->dosage_times == 2)
                    {{ __('twice per').' '.__($drug->dosage_span) }}
                    @endif
                    @if($drug->dosage_times >= 3)
                    {{ $drug->dosage_times.__('times per').' '.__($drug->dosage_span) }}
                    @endif
                    @endif

                    @if(!$drug->dosage_times)
                    {{ '--' }}
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
        @else
        <div>
            {{$patient['name']}} {{ __('has no prescriptions.') }}
        </div>
        @endif
    </div>

</div>
@endsection

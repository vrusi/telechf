@extends('layouts.app')

@section('content')
<div class="container">
    <h1>
        Patients
    </h1>

    <h3>
        {{ $patient['name'].' '.$patient['surname'] }}
    </h3>

    <ul class="nav nav-tabs my-4">
        <li class="nav-item">
            <a class="{{ Request::is('*/profile') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/profile'}}">Profile</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/therapy') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/therapy'}}">Therapy</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/measurements') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/measurements'}}">Measurements</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/charts') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/charts'}}">Charts</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/contacts') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/contacts'}}">Contact</a>
        </li>
    </ul>

    <div class="my-3">
        <h3>
            Monitored parameters
        </h3>

        <table>
            <thead>
                <tr>
                    <th>
                        Parameter
                    </th>
                    <th class="px-3">
                        Safety threshold
                    </th>
                    <th class="pr-3">
                        Therapeutic threshold
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
                        {{ $parameter->name }}
                    </td>

                    {{-- safety --}}
                    <td class="px-3">
                        {{-- both min and max --}}
                        @if($parameter->pivot->threshold_safety_min && $parameter->pivot->threshold_safety_max)
                        {{ $parameter->pivot->threshold_safety_min }} - {{ $parameter->pivot->threshold_safety_max }} {{ $parameter->unit }}

                        {{-- only min --}}
                        @elseif($parameter->pivot->threshold_safety_min && !$parameter->pivot->threshold_safety_max)
                        ≥ {{ $parameter->pivot->threshold_safety_min }} {{ $parameter->unit }}

                        {{-- only max --}}
                        @elseif(!$parameter->pivot->threshold_safety_min && $parameter->pivot->threshold_safety_max)
                        ≤ {{ $parameter->pivot->threshold_safety_max }} {{ $parameter->unit }}

                        {{-- neither --}}
                        @else
                        --
                        @endif
                    </td>


                    {{-- therapeutic --}}
                    <td class="pr-3">
                        {{-- both min and max --}}
                        @if($parameter->pivot->threshold_therapeutic_min && $parameter->pivot->threshold_therapeutic_max)
                        {{ $parameter->pivot->threshold_therapeutic_min }} - {{ $parameter->pivot->threshold_therapeutic_max }} {{ $parameter->unit }}

                        {{-- only min --}}
                        @elseif($parameter->pivot->threshold_therapeutic_min && !$parameter->pivot->threshold_therapeutic_max)
                        ≥ {{ $parameter->pivot->threshold_therapeutic_min }} {{ $parameter->unit }}

                        {{-- only max --}}
                        @elseif(!$parameter->pivot->threshold_therapeutic_min && $parameter->pivot->threshold_therapeutic_max)
                        ≤ {{ $parameter->pivot->threshold_therapeutic_max }} {{ $parameter->unit }}

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

    <div class="my-3">
        <h3>
            Recommendations
        </h3>
        @if($patient->recommendations)
        <div>
            {!! $patient->recommendations !!}
        </div>
        @else
        <div>
            There are no recommendations for {{ $patient['name'] }}.
        </div>
        @endif
    </div>

    <div class="my-3">
        <h3>
            {{ $patient['name'] }} is being treated for
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
                {!! $condition->description !!}
            </div>
        </div>
        @endforeach
        @else
        <div>
            {{ $patient['name'] }} has no conditions.
        </div>
        @endif

    </div>
    <div class="my-3">
        <h3>
            {{ $patient['name'] }} is currently prescribed
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
        @else
        <div>
            {{$patient['name']}} has no prescriptions.
        </div>
        @endif
    </div>

</div>
@endsection

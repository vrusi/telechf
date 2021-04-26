@extends('layouts.app')

@section('content')
<style>
    .alarm-summary {
        border: 2px solid salmon;
    }

    .alarm,
    .alarm-safety {
        background: #ff000020;
        color: rgb(178 34 34 / 87%);
        font-weight: 900;
    }

    .alarm-safety-icon {
        color: firebrick;
        font-weight: 900;
    }

    .faint {
        color: #00000080;

    }

    .alarm-safety .faint {
        color: rgb(178 34 34 / 60%);
    }

    .alarm-therapeutic {
        background: #FEF3E5;
        color: rgb(189 43 0 / 87%);
        font-weight: 900;
    }

    .alarm-therapeutic .faint {
        color: rgb(189 43 0 / 60%);
    }

    .alarm-therapeutic-icon {
        color: rgba(245, 154, 35, 0.87);
        font-weight: 900;
    }

    th,
    td {
        min-width: 70px;
        padding: 0.5rem;
        border-width: 0 0 1px 0;
        border-style: solid;
        border-color: #00000020;
    }

    table {
        width: 100%;
    }

</style>
<div class="container">
    <h1>
        {{ __('Patients') }}
    </h1>

    <h2>
        {{ $patient['name'].' '.$patient['surname'] }}
    </h2>

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

    @if($date)

    <h3>{{ __('Notes for measurements taken on').' '.$date->format('d M Y') }}</h3>

    <h4 class="my-3">{{ __('Measurements') }}</h4>

    <table>
        <thead>
            <tr>
                @foreach($parameters as $parameter)
                @if(strtolower($parameter['name']) != 'ecg')
                <th>
                    {{ __($parameter['name']) }}
                </th>
                @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($measurements as $measurement)
                @if($measurement['triggered_safety_alarm_max'] || $measurement['triggered_safety_alarm_min'])
                <td class="alarm-safety">
                    @elseif($measurement['triggered_therapeutic_alarm_max'] || $measurement['triggered_therapeutic_alarm_min'])
                <td class="alarm-therapeutic">
                    @else
                <td>
                    @endif
                    <div class="row">
                        <div class="col-12">
                        @if ($measurement['value'])
                            @if (is_numeric($measurement['value']))
                            {{ round($measurement['value'], 2) }}
                            @else 
                            {{ __($measurement['value']) }}
                            @endif
                        @else 
                        --
                        @endif
                        </div>
                        @if($measurement['triggered_safety_alarm_max'] || $measurement['triggered_safety_alarm_min'] || $measurement['triggered_therapeutic_alarm_max'] || $measurement['triggered_therapeutic_alarm_min'])
                        <div class="col-12 faint">
                            @if($measurement['triggered_safety_alarm_max'] || $measurement['triggered_therapeutic_alarm_max'])
                            {{ __('too high') }}
                            @elseif($measurement['triggered_safety_alarm_min'] || $measurement['triggered_therapeutic_alarm_min'])
                            {{ __('too low') }}
                            @endif
                        </div>
                        @endif

                        @if( $measurement && $measurement['value'] && array_key_exists('created_at', $measurement) )
                        <div class="col-12 faint">
                            {{ date('H:i', strtotime($measurement['created_at'])) }}
                        </div>
                        @endif
                    </div>
                </td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <h4 class="my-3">{{ __('Notes') }}</h4>
    @if(count($notesAll) > 0)
        @foreach($notesAll as $note)
            @if(count($note['notes']) > 0)
                @php
                $param = null;
                foreach($parameters as $parameter) {
                if ($parameter->id == $note['measurement']['parameter_id']) {
                $param = $parameter;
                }
                }
                @endphp
                <h5> {{ __($param['name']).', '.round($note['measurement']['value'], 2).' '.__($param['unit'])}} </h5>
                    @foreach($note['notes'] as $noteItem)
                        @php
                        $author = $noteItem->author;
                        @endphp
                            <p>
                                <strong>
                                    {{ $author['name'].' '.$author['surname'].', '.date('H:i, d M Y', strtotime($noteItem['created_at'])) }}:
                                </strong>
                                {{ $noteItem['value'] }}
                            </p>
                    @endforeach
            @endif
        @endforeach
    @else
    <p>{{ __('No notes were taken for any of these measurements.') }}</p>
    @endif

    <h4 class="my-3">{{ __('New note') }}</h4>
    @php
    $date = app('request')->input('date');
    @endphp
    <form method="POST" action="{{ route('notes.store', ['patient' => $patient->id, 'date' => $date ]) }}">
        @csrf

        <div class="form-group">
            <label for="measurementSelect">{{ __('Select a measurement to add the note to') }}</label>
            <select class="form-control" id="measurementSelect" name="measurementSelect">
                @foreach($measurements as $paramId => $measurement)
                @if($measurement)
                <option value="{{ $measurement['id'] }}">

                    @php
                    $param = null;
                    foreach($parameters as $parameter) {
                    if ($parameter->id == $paramId) {
                    $param = $parameter;
                    }
                    }
                    @endphp
                    <span>
                        {{ __($param['name']).', '.round($measurement['value'], 2).' '.__($param['unit'])}}
                    </span>
                </option>
                @endif
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="note">{{ __('Note') }}</label>
            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
        </div>
        <a href="{{ route('coordinator.patients.measurements', ['patient' => $patient->id]) }}" class="btn btn-secondary">{{ __('Back') }}</a>
        <input type="hidden" name="patientId" value="{{ $patient->id }}">
        <button type="submit" class="btn btn-primary">
            {{ __('Add note') }}
        </button>
    </form>

    @else

    <p class="text-danger">{{ __('No date was specified. Please pick a day to view the notes.') }}</p>
    <a href="{{ route('coordinator.patients.measurements', ['patient' => $patient->id]) }}" class="btn btn-secondary">{{ __('Back') }}</a>

    @endif

</div>



@endsection

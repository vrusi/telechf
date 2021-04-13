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
        Patients
    </h1>

    <h2>
        {{ $patient['name'].' '.$patient['surname'] }}
    </h2>

    <ul class="nav nav-tabs my-4">
        <li class="nav-item">
            <a class="{{ Request::is('*/profile*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/profile'}}">Profile</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/therapy*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/therapy'}}">Therapy</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/measurements*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/measurements'}}">Measurements</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/charts*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/charts'}}">Charts</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/contacts*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/contacts'}}">Contact</a>
        </li>
    </ul>

    @if($date)

    <h3>Notes for measurements taken on {{ $date->format('d M Y') }}</h3>

    <h4 class="my-3">Measurements</h4>

    <table>
        <thead>
            <tr>
                @foreach($parameters as $parameter)
                @if(strtolower($parameter['name']) != 'ecg')
                <th>
                    {{ $parameter['name'] }}
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

                            {{
                                !$measurement['value']
                                ? '--'
                                : (
                                 is_numeric($measurement['value'])
                                 ? round($measurement['value'], 2)
                                 : $measurement['value']
                                 ) 
                            }}

                        </div>
                        @if($measurement['triggered_safety_alarm_max'] || $measurement['triggered_safety_alarm_min'] || $measurement['triggered_therapeutic_alarm_max'] || $measurement['triggered_therapeutic_alarm_min'])
                        <div class="col-12 faint">
                            @if($measurement['triggered_safety_alarm_max'] || $measurement['triggered_therapeutic_alarm_max'])
                            too high
                            @elseif($measurement['triggered_safety_alarm_min'] || $measurement['triggered_therapeutic_alarm_min'])
                            too low
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


    <h4 class="my-3">Notes</h4>
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

    <h5> {{ $param['name'].', '.round($note['measurement']['value'], 2).' '.$param['unit']}} </h5>
    @foreach($note['notes'] as $noteItem)

    @php
    $author = $noteItem->author;
    @endphp

    <p>
        <strong>
            {{ $author['name'].' '.$author['surname'] }} at {{ date('H:i, d M Y', strtotime($noteItem['created_at'])) }}:
        </strong>
        {{ $noteItem['value'] }}
    </p>


    @endforeach

    @endif

    @endforeach

    @else

    <p>No notes were taken for any of these measurements.</p>

    @endif

    <h4 class="my-3">New note</h4>

    <form method="POST" action="{{ route('notes.store', ['patient' => $patient->id]) }}">
        @csrf

        <div class="form-group">
            <label for="measurementSelect">Select a measurement to add the note to</label>
            <select class="form-control" id="measurementSelect" name="measurementSelect">
                @foreach($measurements as $paramId => $measurement)
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
                        {{ $param['name'].', '.round($measurement['value'], 2).' '.$param['unit']}}
                    </span>
                </option>
                @endforeach
            </select>
        </div>


        <div class="form-group">
            <label for="note">Note</label>
            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
        </div>
        <a href="{{ route('coordinator.patients.measurements', ['patient' => $patient->id]) }}" class="btn btn-secondary">Cancel</a>
        <input type="hidden" name="patientId" value="{{ $patient->id }}">
        <button type="submit" class="btn btn-primary">
            Add note
        </button>
    </form>


    @endif

</div>



@endsection

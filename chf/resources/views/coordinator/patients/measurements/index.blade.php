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

    #summary-table th,
    #summary-table td {
        min-width: 70px;
        padding: 0.5rem;
        border-width: 0 0 1px 0;
        border-style: solid;
        border-color: #00000020;
    }

    table {
        width: 100%;
    }

    table.contact-info td {
        min-width: initial;
        padding: initial;
    }

    .container {
        max-width: 80vw;
    }

    ul {
        list-style-type: none;
    }
</style>


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


    <h3 class="my-3">
        {{ __('Unchecked Alarms') }}
    </h3>

    @php
    $anyUnchecked = $patient->isAnyMeasurementUnchecked();
    @endphp

    @if($anyUnchecked)
    <form method="POST" action="measurements/check" class="my-3">
        @csrf
        <input type="hidden" name="date" value="null">
        <button type="submit" class="btn btn-outline-primary">
            <i class="fas fa-check"></i>
            {{ __('Mark all alarms as checked') }}
        </button>
    </form>

    @foreach($alarms as $date => $alarm)
    @php
    $alarmDate = null;
    foreach ($alarm as $measurement) {
        if (array_key_exists('date', $measurement) && $measurement['date'] != null) {
            $alarmDate = $measurement['date'];
        }
    }
    
    $anyUncheckedInDay = $patient->isAnyMeasurementUncheckedInDay($alarmDate);
    @endphp

    @if($anyUncheckedInDay)
    <div class="alarm-summary p-4 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3>
                    {{ __('An alarm was triggered on') }} {{ $date }}
                </h3>
            </div>
            <div>
                <form method="POST" action="measurements/check">
                    @csrf
                    <input type="hidden" name="date" value="{{ $alarmDate }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-check"></i>
                        {{ __('Mark as checked') }}
                    </button>
                </form>
            </div>
        </div>

        <ul class="px-0">
            @foreach($alarm as $measurement)
            @if( array_key_exists('parameter', $measurement)
            && (
            $measurement['triggered_safety_alarm_max']
            || $measurement['triggered_safety_alarm_min']
            || $measurement['triggered_therapeutic_alarm_max']
            || $measurement['triggered_therapeutic_alarm_min']
            ))
            <li>
                @if($measurement['triggered_safety_alarm_max'])
                <i class="fas fa-chevron-up alarm-safety-icon mr-1"></i>

                @elseif($measurement['triggered_safety_alarm_min'])
                <i class="fas fa-chevron-down alarm-safety-icon mr-1"></i>

                @elseif($measurement['triggered_therapeutic_alarm_max'])
                <i class="fas fa-chevron-up alarm-therapeutic-icon mr-1"></i>

                @elseif($measurement['triggered_therapeutic_alarm_min'])
                <i class="fas fa-chevron-down alarm-therapeutic-icon mr-1"></i>
                @endif

                {{ ucfirst(mb_strtolower(__($measurement['parameter']))) }}
                @if($measurement['triggered_safety_alarm_max'])
                {{ __('over upper safety threshold:') }}
                @elseif($measurement['triggered_safety_alarm_min'])
                {{ __('below lower safety threshold:') }}
                @elseif($measurement['triggered_therapeutic_alarm_max'])
                {{ __('over upper therapeutic threshold:') }}
                @elseif($measurement['triggered_therapeutic_alarm_min'])
                {{ __('below lower therapeutic threshold:') }}
                @endif
                {{ round($measurement['value'], 2).' '.$measurement['unit'] }}

            </li>
            @endif
            @endforeach
        </ul>
    </div>
    @endif
    @endforeach

    <div class="mb-5">
        <h4>
            {{ __('Contact') }}
        </h4>

        <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#modalPatient">
            {{ __('The patient') }}
        </button>

        @foreach($contacts as $contact)
        <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="{{'#modal'.$contact->id}}">
            {{ __('The '.$contact->type) }}
        </button>
        @endforeach
    </div>


    <div class="modal fade" id="modalPatient" tabindex="-1" role="dialog" aria-labelledby="modalPatientLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPatientLabel">
                        {{ __('Patient contact') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="contact-info">
                        <tr>
                            <td class="font-weight-bold">
                                {{ __('Name') }}
                            </td>
                            <td>
                                {{ $patient->name.' '.$patient->surname }}
                            </td>
                        </tr>
                        @if($patient->email)
                        <tr>
                            <td class="font-weight-bold">
                                {{ __('Email') }}
                            </td>
                            <td>
                                <a href="tel:{{$patient->email}}">
                                    {{$patient->email}}
                                </a>
                            </td>
                        </tr>
                        @endif

                        @if($patient->mobile)
                        <tr>
                            <td class="font-weight-bold">
                                {{ __('Mobile') }}
                            </td>
                            <td>
                                <a href="tel:{{$patient->mobile}}">
                                    {{$patient->mobile}}
                                </a>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    @foreach($contacts as $contact)

    <div class="modal fade" id="{{ 'modal'.$contact->id }}" tabindex="-1" role="dialog" aria-labelledby="{{'modal'.$contact->id.'Label'}}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{'modal'.$contact->id.'Label'}}">
                        {{ __(ucfirst($contact->type).' contact') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="contact-info">
                        <tr>
                            <td class="font-weight-bold">
                                {{ __('Name') }}
                            </td>
                            <td>
                                {{$contact->titles_prefix.' '.$contact->name.' '.$contact->surname.' '.$contact->titles_postfix}}
                            </td>
                        </tr>
                        @if($contact->email)
                        <tr>
                            <td class="font-weight-bold">
                                {{ __('Email') }}
                            </td>
                            <td>
                                <a href="mailto:{{$contact->email}}">
                                    {{$contact->email}}
                                </a>
                            </td>
                        </tr>
                        @endif

                        @if($contact->mobile)
                        <tr>
                            <td class="font-weight-bold">
                                {{ __('Mobile') }}
                            </td>
                            <td>
                                <a href="tel:{{$contact->mobile}}">
                                    {{$contact->mobile}}
                                </a>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @else
    <p>{{ __('This patient has no unchecked alarms.') }}</p>
    @endif

    <h3 class="my-3">
        {{ __('Measurements') }}
    </h3>
    <table id="summary-table">
        <thead>
            <tr>
                <th class="pr-4">
                    {{ __('Date') }}
                </th>
                @foreach($parameters as $parameter)
                @if(!(strtolower($parameter['name']) == 'ecg'))
                <th>
                    {{ __($parameter['name']) }} ({{ __($parameter['unit']) }})
                </th>
                @endif
                @endforeach
                <th>
                    {{ __('Swellings') }}
                </th>
                <th>
                    {{ __('Exercise Tolerance') }}
                </th>
                <th>
                    {{ __('Nocturnal Dyspnoea') }}
                </th>
                <th>
                    {{ __('Notes') }}
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary as $date => $day)
            <tr>
                <td>
                    {{ $date }}
                </td>
                @foreach($day as $measurement)

                @if(!array_key_exists('parameter', $measurement) || (array_key_exists('parameter', $measurement) && !(strtolower($measurement['parameter']) == 'ecg')) )
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

                        @if( $measurement && $measurement['value'] && array_key_exists('date', $measurement) )
                        <div class="col-12 faint">
                            {{ date('H:i', strtotime($measurement['date'])) }}
                        </div>
                        @endif
                    </div>
                </td>
                @endif

                @endforeach

                <td>
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="{{ route('notes.index', ['patient' => $patient->id, 'date' => $date ])}}">
                            <i class="fas fa-chevron-circle-right"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $.noConflict();
        $('#summary-table').DataTable({
            fixedColumns: {
                leftColumns: 1,
            },
            responsive: true,
            "ordering": false,
            "language": {
                "url": navigator.language === 'sk' ? '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Slovak.json' : '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json',
            },
        });
    });

</script>

@endsection

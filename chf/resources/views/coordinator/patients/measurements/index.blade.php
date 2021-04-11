@extends('layouts.app')

@section('content')
<style>
    .alarm-summary {
        border: 2px solid salmon;
    }

    .alarm,
    .alarm-safety {
        background: #ff000020;
        color: firebrick;
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
        color: #b2222280;
    }

    .alarm-therapeutic {
        background: #FEF3E5;
        color: rgba(245, 154, 35, 0.87);
        font-weight: 900;
    }

    .alarm-therapeutic .faint {
        color: rgba(245, 154, 35, 0.50);
    }

    .alarm-therapeutic-icon {
        color: rgba(245, 154, 35, 0.87);
        font-weight: 900;
    }

    th,
    td {
        min-width: 70px;
        padding: 1rem;
    }

    table {
        width: 100%;
    }

    table.contact-info td {
        min-width: initial;
        padding: initial;
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
            <a class="{{ Request::is('*/measurements') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/measurements'}}">Measurements</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/charts') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/charts'}}">Charts</a>
        </li>
    </ul>


    @if(count($alarms) > 1)
    <form method="POST" action="measurements/check" class="my-3">
        @csrf
        <input type="hidden" name="date" value="null">
        <button type="submit" class="btn btn-outline-primary">
            <i class="fas fa-check"></i>
            Mark all alarms as checked
        </button>
    </form>
    @endif

    @foreach($alarms as $date => $alarm)
    @php
    $alarmDate = $alarm[0]['date'];
    $anyUnchecked = $patient->isAnyMeasurementUncheckedInDay($alarmDate);
    @endphp

    @if($anyUnchecked)
    <div class="alarm-summary p-4 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3>
                    An alarm was triggered on {{ $date }}
                </h3>
            </div>
            <div>
                <form method="POST" action="measurements/check">
                    @csrf
                    <input type="hidden" name="date" value="{{ $alarmDate }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-check"></i>
                        Mark as checked
                    </button>
                </form>
            </div>
        </div>

        <ul>
            @foreach($alarm as $measurement)
            @if( array_key_exists('parameter', $measurement)
            && (
            $measurement['alarmSafetyMax']
            || $measurement['alarmSafetyMin']
            || $measurement['alarmTherapeuticMax']
            || $measurement['alarmTherapeuticMin']
            ))
            <li>
                {{ ucfirst(strtolower($measurement['parameter'])) }}
                @if($measurement['alarmSafetyMax'])
                over maximum safety threshold:
                @elseif($measurement['alarmSafetyMin'])
                below minimum safety threshold:
                @elseif($measurement['alarmTherapeuticMax'])
                over maximum therapeutic threshold:
                @elseif($measurement['alarmTherapeuticMin'])
                below minimum therapeutic threshold:
                @endif
                {{ round($measurement['value'], 2).' '.$measurement['unit'] }}

                @if($measurement['alarmSafetyMax'])
                <i class="fas fa-chevron-up alarm-safety-icon"></i>

                @elseif($measurement['alarmSafetyMin'])
                <i class="fas fa-chevron-down alarm-safety-icon"></i>

                @elseif($measurement['alarmTherapeuticMax'])
                <i class="fas fa-chevron-up alarm-therapeutic-icon"></i>

                @elseif($measurement['alarmTherapeuticMin'])
                <i class="fas fa-chevron-down alarm-therapeutic-icon"></i>
                @endif

            </li>
            @endif
            @endforeach
        </ul>
    </div>
    @endif
    @endforeach

    <div class="mb-5">
        <h4>
            Contact
        </h4>

        <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#modalPatient">
            The patient
        </button>

        @foreach($contacts as $contact)
        <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="{{'#modal'.$contact->id}}">
            The {{ $contact->type }}
        </button>
        @endforeach
    </div>


    <div class="modal fade" id="modalPatient" tabindex="-1" role="dialog" aria-labelledby="modalPatientLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPatientLabel">
                        Patient contact
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="contact-info">
                        <tr>
                            <td class="font-weight-bold">
                                Name
                            </td>
                            <td>
                                {{ $patient->name.' '.$patient->surname }}
                            </td>
                        </tr>
                        @if($patient->email)
                        <tr>
                            <td class="font-weight-bold">
                                Email
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
                                Mobile
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Chat</button>
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
                        {{ ucfirst($contact->type) }} contact
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="contact-info">
                        <tr>
                            <td class="font-weight-bold">
                                Name
                            </td>
                            <td>
                                {{$contact->titles_prefix.' '.$contact->name.' '.$contact->surname.' '.$contact->titles_postfix}}
                            </td>
                        </tr>
                        @if($contact->email)
                        <tr>
                            <td class="font-weight-bold">
                                Email
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
                                Mobile
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach



    <h3>
        Measurements
    </h3>
    <table id="summary-table">
        <thead>
            <tr>
                <th class="pr-4">
                    Date
                </th>
                @foreach($parameters as $parameter)
                @if(!(strtolower($parameter['name']) == 'ecg'))
                <th>
                    {{ $parameter['name'] }} ({{ $parameter['unit'] }})
                </th>
                @endif
                @endforeach
                <th>
                    Swellings
                </th>
                <th>
                    Exercise Tolerance
                </th>
                <th>
                    Nocturnal Dyspnoea
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
                @if($measurement['alarmSafetyMax'] || $measurement['alarmSafetyMin'])
                <td class="alarm-safety">
                    @elseif($measurement['alarmTherapeuticMax'] || $measurement['alarmTherapeuticMin'])
                <td class="alarm-therapeutic">
                    @else
                <td>
                    @endif
                    {{
                        !$measurement['value']
                        ? '--'
                        : (
                           is_numeric($measurement['value'])
                           ? round($measurement['value'], 2)
                           : $measurement['value']
                           ) 
                           }}
                </td>
                @endif
                @endforeach
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
                leftColumns: 1
            }
            , responsive: true
            , "ordering": false
        , });
    });

</script>

@endsection

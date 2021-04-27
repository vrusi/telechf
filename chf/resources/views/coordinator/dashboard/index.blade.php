@extends('layouts.app')

@section('content')
<style>
    .alarm-safety {
        background: #ff000020;
        color: rgb(178 34 34 / 87%);
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

    .container {
        max-width: 80vw;
    }

    .swatch {
        width: 20px;
        height: 20px;
    }

    .swatch.safety {
        background: #ff000020;
        border: 2px solid rgb(178 34 34 / 87%);
        border-radius: 100%;
    }

    .swatch.therapeutic {
        background: #FEF3E5;
        border: 2px solid rgb(189 43 0 / 87%);
        border-radius: 100%;
    }

    .modal-container {
        color: initial;
    }
</style>

<div class="container pb-5">
    <h1>{{ __('Dashboard') }}</h1>

    <div x-data="tab()">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a id="alarms-link" @click="tabSwitch()" :class="{'active': tab == 'alarms'}" class="nav-link active"
                    href="">{{ __('Alarms') }}</a>
            </li>

            <li class="nav-item">
                <a id="extras-link" @click="tabSwitch()" :class="{'active': tab == 'extras'}" class="nav-link"
                    href="">{{ __('Extra measurements') }}</a>
            </li>
        </ul>

        <div class="my-4">

            <div class="d-flex align-content-center">
                <div class="swatch safety mr-1">
                </div>
                <div>
                    {{ __('Alarm triggered by crossing safety thresholds.') }}
                </div>
            </div>

            <div class="d-flex align-content-center">
                <div class="swatch therapeutic mr-1">
                </div>
                <div>
                    {{ __('Alarm triggered by crossing therapeutic thresholds.') }}
                </div>
            </div>

        </div>

        <div x-show="tab=='alarms'">
            <table id="alarms-table">
                <thead>
                    <tr>
                        <th class="pr-4">
                            {{ __('Patient') }}
                        </th>
                        <th>
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
                            {{ __('Dyspnoea while lying down') }}
                        </th>
                        <th>
                            {{ __('Checked') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alarms as $date => $patients)
                    @foreach($patients as $patientId => $patientRecord)
                    <tr>
                        <!-- PATIENT NAME -->
                        <td>
                            <a href="{{'patients/'.$patientId.'/measurements'}}"
                                class="d-flex justify-content-between align-items-center">
                                {{ $patientRecord['patient']['name'].' '.$patientRecord['patient']['surname'] }}</a>
                        </td>

                        <!-- MEASUREMENT DATE -->
                        @php
                        $dateFormatted = date('d M', strtotime($date));
                        @endphp
                        <td> {{ $dateFormatted }} </td>


                        <!-- PARAMETERS MEASUREMENTS -->
                        @foreach($patientRecord['measurements'] as $measurement)

                        @php
                        $hasParameter = array_key_exists('parameter', $measurement);
                        $isECG = $hasParameter && strtolower($measurement['parameter']) == 'ecg';
                        @endphp

                        @if(!$isECG)
                        <!-- CHECK SAFETY ALARM -->
                        @if($measurement['triggered_safety_alarm_max'] || $measurement['triggered_safety_alarm_min'])
                        <td class="alarm-safety">
                            <!-- CHECK THERAPEUTIC ALARM -->
                            @elseif($measurement['triggered_therapeutic_alarm_max'] ||
                            $measurement['triggered_therapeutic_alarm_min'])
                        <td class="alarm-therapeutic">
                            @else
                        <td>
                            @endif

                            <div class="row">
                                <!-- MEASUREMENT VALUE -->
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

                                <!-- ALARM DESCRIPTION -->
                                @if($measurement['alarm'])
                                <div class="col-12 faint">
                                    {{
                            ( $measurement['triggered_safety_alarm_max'] || $measurement['triggered_therapeutic_alarm_max'])
                            ? __('too high')
                            : __('too low')
                            }}
                                </div>
                                @endif

                                <!-- MEASUREMENT TIME -->
                                @if( $measurement['value'] && array_key_exists('date', $measurement) )
                                <div class="col-12 faint">
                                    {{ date('H:i', strtotime($measurement['date'])) }}
                                </div>
                                @endif
                            </div>
                        </td>
                        @endif
                        @endforeach

                        <!-- WAS ALARM CHECKED -->
                        <td>
                            @php
                            $anyUnchecked = $patientRecord['patient']->isAnyMeasurementUncheckedInDay($date);
                            @endphp
                            @if($anyUnchecked)
                            <a href="{{'patients/'.$patientId.'/measurements'}}" class="d-flex align-items-center">
                                <div class="mr-1">
                                    {{ __('Check') }}
                                </div>
                                <i class="fas fa-caret-right"></i>
                            </a>
                            @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-check"></i>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
        <div x-show="tab=='extras'">
            <table id="extras-table">
                <thead>
                    <tr>
                        <th class="pr-4">
                            {{ __('Patient') }}
                        </th>
                        <th>
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
                            {{ __('Dyspnoea while lying down') }}
                        </th>
                        <th>
                            {{ __('Checked') }}
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($extras as $date => $patients)
                    @foreach($patients as $patientId => $patientRecord)
                    <tr>
                        <!-- PATIENT NAME -->
                        <td>
                            <a href="{{'patients/'.$patientId.'/measurements'}}"
                                class="d-flex justify-content-between align-items-center">
                                {{ $patientRecord['patient']['name'].' '.$patientRecord['patient']['surname'] }}</a>
                        </td>

                        <!-- MEASUREMENT DATE -->
                        @php
                        $dateFormatted = date('d M', strtotime($date));
                        @endphp
                        <td> {{ $dateFormatted }} </td>


                        <!-- PARAMETERS MEASUREMENTS -->
                        @foreach($patientRecord['measurements'] as $measurement)

                        @php
                        $hasParameter = array_key_exists('parameter', $measurement);
                        $isECG = $hasParameter && strtolower($measurement['parameter']) == 'ecg';
                        @endphp

                        @if(!$isECG)
                        <!-- CHECK SAFETY ALARM -->
                        @if($measurement['triggered_safety_alarm_max'] || $measurement['triggered_safety_alarm_min'])
                        <td class="alarm-safety">
                            <!-- CHECK THERAPEUTIC ALARM -->
                            @elseif($measurement['triggered_therapeutic_alarm_max'] ||
                            $measurement['triggered_therapeutic_alarm_min'])
                        <td class="alarm-therapeutic">
                            @else
                        <td>
                            @endif

                            <div class="row">
                                <!-- MEASUREMENT VALUE -->
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

                                <!-- ALARM DESCRIPTION -->
                                @if($measurement && $measurement['alarm'])
                                <div class="col-12 faint">
                                    {{
                                        ( $measurement['triggered_safety_alarm_max'] || $measurement['triggered_therapeutic_alarm_max'])
                                        ? __('too high')
                                        : __('too low')
                                        }}
                                </div>
                                @endif

                                <!-- MEASUREMENT TIME -->
                                @if( $measurement && $measurement['value'] && array_key_exists('date', $measurement) )
                                <div class="col-12 faint">
                                    {{ date('H:i', strtotime($measurement['date'])) }}
                                </div>
                                @endif

                                @if( $measurement && $measurement['value'] && array_key_exists('extra_description',
                                $measurement) && $measurement['extra_description'])

                                <div class="col-12 modal-container">

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal"
                                        data-target="#extraDescriptionModal">
                                        {{ __('Show description') }}
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="extraDescriptionModal" tabindex="-1" role="dialog"
                                        aria-labelledby="extraDescriptionModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="extraDescriptionModalLabel">
                                                        {{ $patientRecord['patient']['name'].' '.$patientRecord['patient']['surname'] }}
                                                        @if ($patientRecord['patient']['sex'] == "male")
                                                            {{ __('said the following') }}
                                                        @else
                                                            {{ __('said the following ') }}
                                                        @endif
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ $measurement['extra_description'] }}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary"
                                                        data-dismiss="modal">{{ __('Close') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                @endif
                            </div>
                        </td>
                        @endif
                        @endforeach

                        <!-- WAS ALARM CHECKED -->
                        <td>
                            @php
                            $anyUnchecked = $patientRecord['patient']->isAnyMeasurementUncheckedInDay($date);
                            @endphp
                            @if($anyUnchecked)
                            <a href="{{'patients/'.$patientId.'/measurements'}}" class="d-flex align-items-center">
                                <div class="mr-1">
                                    {{ __('Check') }}
                                </div>
                                <i class="fas fa-caret-right"></i>
                            </a>
                            @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-check"></i>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>

<script>
    $(document).ready(function() {

        $.noConflict();

        $('#alarms-table').DataTable({
            fixedColumns: {
                leftColumns: 1,
            },
            responsive: true,
            "ordering": false,
            "language": {
                "url": navigator.language === 'sk' ? '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Slovak.json' : '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json',
            },
        });

        $('#extras-table').DataTable({
            fixedColumns: {
                leftColumns: 1,
            },
            responsive: true,
            "ordering": false,
            "language": {
                "url": navigator.language === 'sk' ? '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Slovak.json' : '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json',
            },
        });

        $('#alarms-link').click(function() {
            return false;
        });

        $('#extras-link').click(function() {
            return false;
        });

    });



    function tab() {
        var urlParams = new URLSearchParams(window.location.search);
        var url = new URL(window.location);
        if (!url.searchParams.get('tab')) {
            history.pushState(null, '', 'dashboard?tab=alarms');
        }

        return {
            tab: url.searchParams.get('tab') || 'alarms'
            , tabSwitch() {
                if (this.tab === 'extras') {
                    this.tab = 'alarms';
                    history.pushState(null, '', 'dashboard?tab=alarms');

                } else if (this.tab === 'alarms') {
                    this.tab = 'extras';
                    history.pushState(null, '', 'dashboard?tab=extras');
                }

            }
        , }
    }

</script>
@endsection
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

</style>


<div class="container pb-5">
    <h1>Dashboard</h1>

    {{-- todo nav --}}

    <h2>New Alarms</h2>


    <div class="my-4">

        <div class="d-flex align-content-center">
            <div class="swatch safety mr-1">
            </div>
            <div>
                Alarm triggered by crossing safety thresholds.
            </div>
        </div>

        <div class="d-flex align-content-center">
            <div class="swatch therapeutic mr-1">
            </div>
            <div>
                Alarm triggered by crossing therapeutic thresholds.
            </div>
        </div>

    </div>

    <table id="alarms-table">
        <thead>
            <tr>
                <th class="pr-4">
                    Patient
                </th>
                <th>
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
                <th>
                    Checked
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($alarms as $date => $patients)
            @foreach($patients as $patientId => $patientRecord)
            <tr>
                <!-- PATIENT NAME -->
                <td>
                    <a href="{{'patients/'.$patientId.'/measurements'}}" class="d-flex justify-content-between align-items-center">
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
                    @elseif($measurement['triggered_therapeutic_alarm_max'] || $measurement['triggered_therapeutic_alarm_min'])
                <td class="alarm-therapeutic">
                    @else
                <td>
                    @endif

                    <div class="row">
                        <!-- MEASUREMENT VALUE -->
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

                        <!-- ALARM DESCRIPTION -->
                        @if($measurement['alarm'])
                        <div class="col-12 faint">
                            {{
                            ( $measurement['triggered_safety_alarm_max'] || $measurement['triggered_therapeutic_alarm_max'])
                            ? 'too high'
                            : 'too low'
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
                            Check
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

<script>
    $(document).ready(function() {

        $.noConflict();

        $('#alarms-table').DataTable({
            fixedColumns: {
                leftColumns: 1
            }
            , responsive: true
            , "ordering": false
        , });
    });

</script>
@endsection

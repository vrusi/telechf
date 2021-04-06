@extends('layouts.app')

@section('content')
<style>
    .alarm-safety {
        background: #ff000020;
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
        max-width: 70vw;
    }

</style>


<div class="container pb-5">
    <h1>Dashboard</h1>

    {{-- todo nav --}}

    <h2>New Alarms</h2>

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
            @foreach($alarms as $day)
            <tr>
                <td>
                    {{ $day['patient']['name'].' '.$day['patient']['surname'] }}
                </td>
                <td>
                    {{$day['date']}}
                </td>

                @foreach($day['measurements'] as $measurement)
                @if(!$measurement)
                <td>
                    --
                </td>

                @else






                @if($measurement['triggered_safety_alarm_max'] || $measurement['triggered_safety_alarm_min'])
                <td class="alarm-safety">
                    @elseif($measurement['triggered_therapeutic_alarm_max'] || $measurement['triggered_therapeutic_alarm_min'])
                <td class="alarm-therapeutic">
                    @else

                <td>

                    @endif

                    <div class="row">

                        <div class="col-12">
                            <p>
                                {{
                                !$measurement['value']
                                ? '--'
                                : (
                                 is_numeric($measurement['value'])
                                 ? round($measurement['value'], 2)
                                 : $measurement['value']
                                 ) 
                            }}
                            </p>
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

                        @if( $measurement && array_key_exists('created_at', $measurement) )
                        <div class="col-12 faint">
                            {{ date('H:i:s', strtotime($measurement['created_at'])) }}
                        </div>
                        @endif
                </td>

                @endif



                @endforeach
                <td>
                    @if($day['anyUnchecked'])
                    <a href="{{'patients/'.$day['patient']->id.'/measurements'}}">Check <i class="fas fa-caret-right"></i></a>
                    @else
                    <a href="{{'patients/'.$day['patient']->id.'/measurements'}}">Checked <i class="fas fa-check"></i></a>
                    @endif
                </td>
            </tr>
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

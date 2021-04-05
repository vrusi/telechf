@extends('layouts.app')

@section('content')
<style>
    .alarm-safety {
        background: #ff000020;
        color: firebrick;
        font-weight: 900;
    }

    .alarm-therapeutic {
        background: #FEF3E5;
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

</style>


<div class="container">
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
                    Source of alarm
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
                


                @if($measurement['triggered_safety_alarm_max'] || $measurement['triggered_safety_alarm_min'])
                <td class="alarm-safety">
                    @elseif($measurement['triggered_therapeutic_alarm_max'] || $measurement['triggered_therapeutic_alarm_min'])
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
                @endforeach
            </tr>

            @endforeach


        </tbody>

    </table>


</div>

<script>

</script>
@endsection

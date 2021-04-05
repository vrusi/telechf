@extends('layouts.app')

@section('content')
<style>
    .alarm, .alarm-safety {
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
        padding: 1rem;
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
                @if($measurement['alarm'])
                <td class="alarm">
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

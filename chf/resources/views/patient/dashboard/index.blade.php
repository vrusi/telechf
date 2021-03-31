@extends('layouts.app')

@section('content')

<style>
    .alarm {
        background: #ff000020;
        color: firebrick;
        font-weight: 900;
    }

</style>

<div class="container">
    <div class="py-12" x-data="{ tab: 'alarms' }">

        <ul class="nav nav-tabs">
            <div @click="tab='alarms'">
                <li class="nav-item">
                    <a :class="{'active': tab == 'alarms'}" class="nav-link active" href="#">Alarms</a>
                </li>
            </div>
            <div @click="tab='summary'">
                <li class="nav-item">
                    <a :class="{'active': tab == 'summary'}" class="nav-link" href="#">Summary</a>
                </li>
            </div>

        </ul>


        <div class="mt-5">
            <div x-show="tab=='alarms'">
                These measurements you took have triggered alarms
            </div>
            <div x-show="tab=='summary'">
                These are your latest measurements

                <table id="summary-table">
                    <thead>
                        <tr>
                            <th>
                                Date
                            </th>
                            @foreach($parameters as $parameter)
                            <th>
                                {{ $parameter['name'] }} ({{ $parameter['unit'] }})
                            </th>
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
                                {{$date}}
                            </td>
                            @foreach($day as $parameter)
                            @if($parameter['alarm'])
                            <td class="alarm">
                                {{$parameter['value'] ?? '--' }}
                            </td>
                            @else
                            <td>
                                {{$parameter['value'] ?? '--' }}
                            </td>
                            @endif
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>


    </div>
</div>

</div>

<script>
    $(document).ready(function() {
        $.noConflict();
        $('#summary-table').DataTable({
            fixedColumns: {
                leftColumns: 1
            }
            , responsive: true,
              "order": [[ 0, 'dsc' ]]

        });

        new $.fn.dataTable.Buttons(table, {
            buttons: [
                'copy', 'excel', 'pdf'
            ]
        });
    });

</script>
@endsection

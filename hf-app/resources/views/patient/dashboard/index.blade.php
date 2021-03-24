@extends('layouts.app')

@section('content')
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
                                {{$parameter['name']}}
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
                            <td>
                                {{$parameter['value'] ?? '--' }}
                            </td>
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
        $('#summary-table').DataTable();
    });

</script>
@endsection

@extends('layouts.app')

@section('content')

<style>
    .alarm {
        background: #ff000020;
        color: firebrick;
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
    <div x-data="tab()">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a id="alarms-link" @click="tabSwitch()" :class="{'active': tab == 'alarms'}" class="nav-link active" href="">Alarms</a>
            </li>

            <li class="nav-item">
                <a id="summary-link" @click="tabSwitch()" :class="{'active': tab == 'summary'}" class="nav-link" href="">Summary</a>
            </li>
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
                                {{$measurement['value'] ?? '--' }}
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

        $('#alarms-link').click(function() {
            return false;
        });

        $('#summary-link').click(function() {
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
                if (this.tab === 'summary') {
                    this.tab = 'alarms';
                    history.pushState(null, '', 'dashboard?tab=alarms');

                } else if (this.tab === 'alarms') {
                    this.tab = 'summary';
                    history.pushState(null, '', 'dashboard?tab=summary');
                }

            }
        , }
    }

</script>
@endsection

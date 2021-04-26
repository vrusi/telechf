@extends('layouts.app')

@section('content')

<style>
    .alarm {
        background: #ff000020;
        color: firebrick;
        font-weight: 900;
    }

    .faint {
        color: #00000080;
    }

    .alarm .faint {
        color: rgb(178 34 34 / 60%);
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
                <a id="alarms-link" @click="tabSwitch()" :class="{'active': tab == 'alarms'}" class="nav-link active" href="">{{ __('Alarms') }}</a>
            </li>

            <li class="nav-item">
                <a id="summary-link" @click="tabSwitch()" :class="{'active': tab == 'summary'}" class="nav-link" href="">{{ __('Summary') }}</a>
            </li>
        </ul>

        <div class="mt-5">
            <div x-show="tab=='alarms'">
                <h3 class="pb-5">
                    @if(empty($alarms))
                    {{ __('None of your measurements have exceeded any of your goal values') }}
                    @else
                    {{ __('These measurements have exceeded your goal values') }}
                    @endif
                </h3>

                <table id="alarms-table">
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
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($alarms as $date => $day)
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
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <div x-show="tab=='summary'">
                <h3 class="pb-5">
                    @if(empty($summary))
                    {{ __('You have not taken any measurements yet') }}
                    @else
                    {{ __('These are your latest measurements') }}
                    @endif
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
                leftColumns: 1,
            },
            responsive: true,
            "ordering": false,
            "language": {
                "url": navigator.language === 'sk' ? '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Slovak.json' : '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json',
            },
        });

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

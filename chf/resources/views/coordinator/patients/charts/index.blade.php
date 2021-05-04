@extends('layouts.app')

@section('content')
    <style>
        .sticky {
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            width: 100%;
            background: #f8fafc;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }

        .hidden {
            display: none;
        }

        .visible {
            display: block;
        }

    </style>

    <div class="container">
        <h1>
            {{ __('Patients') }}
        </h1>

        <h2>
            {{ $patient['name'] . ' ' . $patient['surname'] }}
        </h2>

        <ul class="nav nav-tabs my-4">
            <li class="nav-item">
                <a class="{{ Request::is('*/profile*') ? 'nav-link active' : 'nav-link' }}"
                    href="{{ '/coordinator/patients/' . $patient['id'] . '/profile' }}">{{ __('Profile') }}</a>
            </li>
            <li class="nav-item">
                <a class="{{ Request::is('*/therapy*') ? 'nav-link active' : 'nav-link' }}"
                    href="{{ '/coordinator/patients/' . $patient['id'] . '/therapy' }}">{{ __('Therapy') }}</a>
            </li>
            <li class="nav-item">
                <a class="{{ Request::is('*/measurements*') ? 'nav-link active' : 'nav-link' }}"
                    href="{{ '/coordinator/patients/' . $patient['id'] . '/measurements' }}">{{ __('Measurements') }}</a>
            </li>
            <li class="nav-item">
                <a class="{{ Request::is('*/charts*') ? 'nav-link active' : 'nav-link' }}"
                    href="{{ '/coordinator/patients/' . $patient['id'] . '/charts' }}">{{ __('Charts') }}</a>
            </li>
            <li class="nav-item">
                <a class="{{ Request::is('*/contacts*') ? 'nav-link active' : 'nav-link' }}"
                    href="{{ '/coordinator/patients/' . $patient['id'] . '/contacts' }}">{{ __('Contact') }}</a>
            </li>
        </ul>

        <div id="filter" class="py-2">
            <div class="container px-0">
                <h3 id="filter-title">
                    {{ __('Filter') }}
                </h3>

                <div x-data="selectFilter()">
                    <form method="POST" action="charts/filter">
                        @csrf
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                {{ __('Plot data from last') }}
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio1" value="1"
                                    x-bind:checked="select1">
                                <label class="form-check-label" for="inlineRadio1">{{ __('week ') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio2" value="2"
                                    x-bind:checked="select2">
                                <label class="form-check-label" for="inlineRadio2">{{ __('month ') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio3" value="3"
                                    x-bind:checked="select3">
                                <label class="form-check-label" for="inlineRadio3">{{ __('three months') }}</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio4" value="4"
                                    x-bind:checked="select4">
                                <label class="form-check-label" for="inlineRadio4">{{ __('six months') }}</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio5" value="5"
                                    x-bind:checked="select5">
                                <label class="form-check-label" for="inlineRadio5">{{ __('all time data') }}</label>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-outline-secondary">{{ __('Plot') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <h3>
            {{ __('Charts') }}
        </h3>

        @foreach ($charts as $chart)
            <div id="{{ 'chart-' . $chart['name'] }}" class="mt-5">
            </div>
        @endforeach

        {{-- @if ($conditions && (count($conditions['swellings']) > 0 || count($conditions['exercise']) > 0 || count($conditions['dyspnoea']) > 0))
            <div class="d-flex justify-content-end align-items-center px-5 pt-5 mt-5 bg-white">
                <div>
                    <form method="POST" action="{{ '/coordinator/patients/' . $patient['id'] . '/charts#chart-conditions' }}">
                        @csrf
                        <div class="form-group">
                            <label for="conditionsDateChoice">{{ __('Select a measurement by date') }}</label>
                            <select class="form-control" id="conditionsDateChoice" name="conditionsDateChoice">
                                @foreach ($conditions['dates'] as $conditionsAvailableDate)
                                    <option value="{{ $conditionsAvailableDate }}">
                                        {{ $conditionsAvailableDate->format('d M Y') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" value="{{ $patient['id'] }}">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            {{ __('Select') }}
                        </button>
                    </form>
                </div>
            </div>
            <div id="chart-conditions">
            </div>
        @endif --}}

        @if ($chartECG)
            <div class="d-flex justify-content-between  align-items-center  px-5 pt-5 pb-1 mt-5 bg-white">
                <div>
                    <table>
                        <tr>
                            <td class="font-weight-bold pr-2">
                                {{ __('Marker') }} 1:
                            </td>
                            <td id="marker1">
                                --
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold pr-2">
                                {{ __('Marker') }} 2:
                            </td>
                            <td id="marker2">
                                --
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold pr-2">
                                {{ __('Difference') }}:
                            </td>
                            <td id="markerResult1">
                                --
                            </td>
                        </tr>
                    </table>
                </div>

                <div>
                    <table>
                        <tr>
                            <td class="font-weight-bold pr-2">
                                {{ __('Marker') }} 3:
                            </td>
                            <td id="marker3">
                                --
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold pr-2">
                                {{ __('Marker') }} 4:
                            </td>
                            <td id="marker4">
                                --
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold pr-2">
                                {{ __('Difference') }}:
                            </td>
                            <td id="markerResult2">
                                --
                            </td>
                        </tr>
                    </table>
                </div>

                <div>
                    <table>
                        <tr>
                            <td class="font-weight-bold pr-2">
                                {{ __('Marker') }} 5:
                            </td>
                            <td id="marker5">
                                --
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold pr-2">
                                {{ __('Marker') }} 6:
                            </td>
                            <td id="marker6">
                                --
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold pr-2">
                                {{ __('Difference') }}:
                            </td>
                            <td id="markerResult3">
                                --
                            </td>
                        </tr>
                    </table>
                </div>

                <div>
                    <form method="POST" action="{{ '/coordinator/patients/' . $patient['id'] . '/charts#chart-ecg' }}">
                        @csrf
                        <div class="form-group">
                            <label for="ecgDateChoice">{{ __('Select a measurement by date') }}</label>
                            <select class="form-control" id="ecgDateChoice" name="ecgDateChoice">
                                @foreach ($ecgAvailableDates as $ecgAvailableDate)
                                    <option value="{{ $ecgAvailableDate['date'] }}">
                                        {{ $ecgAvailableDate['dateFormatted'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" value="{{ $patient['id'] }}">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            {{ __('Select') }}
                        </button>
                    </form>
                </div>
            </div>
            <div class="ecg-chart" id="chart-ecg">
                <div class="d-flex align-items-center justify-content-center pr-5 pb-5 pl-5 mb-5 bg-white">
                    <div class="d-flex align-items-center mx-3">
                        <div class="mr-2" style="width: 30px; height: 30px; background-color: #b4aee840;">
                        </div>
                        <div class="d-flex">
                            <div class="mr-1">
                                {{ __('Pause detected') }}
                            </div>
                            @if (count($chartECG['eventsP']))
                                <div class="text-danger">
                                    ({{ count($chartECG['eventsP']) }})
                                </div>
                            @else
                                <div>
                                    ({{ count($chartECG['eventsP']) }})
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex align-items-center mx-3">
                        <div class="mr-2" style="width: 30px; height: 30px; background-color: #f0c92940;">
                        </div>
                        <div class="d-flex">
                            <div class="mr-1">
                                {{ __('Bradycardia detected') }}
                            </div>
                            @if (count($chartECG['eventsB']))
                                <div class="text-danger">
                                    ({{ count($chartECG['eventsB']) }})
                                </div>
                            @else
                                <div>
                                    ({{ count($chartECG['eventsB']) }})
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex align-items-center mx-3">
                        <div class="mr-2" style="width: 30px; height: 30px; background-color: #f3918940;">
                        </div>
                        <div class="d-flex">
                            <div class="mr-1">
                                {{ __('Tachycardia detected') }}
                            </div>
                            @if (count($chartECG['eventsT']))
                                <div class="text-danger">
                                    ({{ count($chartECG['eventsT']) }})
                                </div>
                            @else
                                <div>
                                    ({{ count($chartECG['eventsT']) }})
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex align-items-center mx-3">
                        <div class="mr-2" style="width: 30px; height: 30px; background-color: #04658240;">
                        </div>
                        <div class="d-flex">
                            <div class="mr-1">
                                {{ __('Atrial fibrillation detected') }}
                            </div>
                            @if (count($chartECG['eventsAF']))
                                <div class="text-danger">
                                    ({{ count($chartECG['eventsAF']) }})
                                </div>
                            @else
                                <div>
                                    ({{ count($chartECG['eventsAF']) }})
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        @else
            <div class="mb-5"></div>
        @endif
    </div>

    <script>
        // make filter sticky
        var filter = $('#filter')[0];
        var filterTitle = $('#filter-title')[0];
        var sticky = filter.offsetTop;

        window.onscroll = () => {
            if (window.pageYOffset > sticky) {
                filter.classList.add("sticky");
                filterTitle.classList.add("hidden");
                filterTitle.classList.remove("visible");
            } else {
                filter.classList.remove("sticky");
                filterTitle.classList.add("visible");
                filterTitle.classList.remove("hidden");
            }
        };

        // select default radio from filter options
        option = {!! $filterOption !!};

        function selectFilter() {
            return {
                select1: option == 1,
                select2: option == 2,
                select3: option == 3,
                select4: option == 4,
                select5: option == 5,
            };
        }

        // set up the charts
        charts = {!! $charts_encoded !!};

        names_sk = {
            'Systolic Blood Pressure': 'Systolický krvný tlak',
            'Diastolic Blood Pressure': 'Diastolický krvný tlak',
            'Heart Rate': 'Tep srdca',
            'Heart Rate': 'Tep srdca',
            'SpO2': 'SpO2',
            'Weight': 'Hmotnosť',
            'Weight Change': 'Zmena hmotnosti',
            'ECG': 'EKG',
        };

        units_sk = {
            'mmHg': 'mmHg',
            'bpm': 'úderov za minútu',
            '%': '%',
            'kg': 'kg',
            'mV': 'mV',
        };

        for (chart of charts) {
            name = chart['name'];
            unit = chart['unit'];
            values = chart['values'];
            dates = chart['dates'];
            max_therapeutic = chart['max_therapeutic'];
            min_therapeutic = chart['min_therapeutic'];
            max_safety = chart['max_safety'];
            min_safety = chart['min_safety'];

            var plot = {
                x: dates,
                y: values,
                mode: 'lines',
                name: navigator.language === 'sk' ? names_sk[name] : name,
            };

            var lower_threshold_therapeutic = min_therapeutic ? {
                    x: dates,
                    y: Array(dates.length).fill(min_therapeutic),
                    mode: 'lines',
                    line: {
                        dash: 'dot',
                    },
                    name: navigator.language === 'sk' ? 'Spodný terapeutický limit' : 'Lower therapeutic threshold',
                } :
                null;

            var upper_threshold_therapeutic = max_therapeutic ? {
                    x: dates,
                    y: Array(dates.length).fill(max_therapeutic),
                    mode: 'lines',
                    line: {
                        dash: 'dot',
                    },
                    name: navigator.language === 'sk' ? 'Horný terapeutický limit' : 'Upper therapeutic threshold',
                } :
                null;

            var upper_threshold_safety = max_safety ? {
                    x: dates,
                    y: Array(dates.length).fill(max_safety),
                    mode: 'lines',
                    line: {
                        dash: 'dot',
                    },
                    name: navigator.language === 'sk' ? 'Horný bezpečnostný limit' : 'Upper safety threshold',
                } :
                null;

            var lower_threshold_safety = min_safety ? {
                    x: dates,
                    y: Array(dates.length).fill(min_safety),
                    mode: 'lines',
                    line: {
                        dash: 'dot',
                    },
                    name: navigator.language === 'sk' ? 'Spodný bezpečnostný limit' : 'Lower safety threshold',
                } :
                null;

            var layout = {
                title: {
                    text: navigator.language === 'sk' ? names_sk[name] : name,
                },
                xaxis: {
                    title: {
                        text: navigator.language === 'sk' ? 'Dátum' : 'Date',
                    },
                },
                yaxis: {
                    title: {
                        text: navigator.language === 'sk' ? 'Hodnota (' + units_sk[unit] + ')' : 'Value (' + unit + ')',
                    },
                },
                showlegend: true,
                legend: {
                    "orientation": "h",
                    xanchor: "center",
                    yanchor: "top",
                    y: -0.3,
                    x: 0.5,
                },
            };

            traces = [plot];

            if (lower_threshold_therapeutic) {
                traces.push(lower_threshold_therapeutic);
            }

            if (upper_threshold_therapeutic) {
                traces.push(upper_threshold_therapeutic);
            }

            if (lower_threshold_safety) {
                traces.push(lower_threshold_safety);
            }

            if (upper_threshold_safety) {
                traces.push(upper_threshold_safety);
            }

            Plotly.newPlot('chart-' + name, traces, layout);
        }

        // fill the conditions plot
       //

        //var plotSwellings = conditions['swellings'].length > 0 ? {
        //    x: Object.keys(conditions['swellings'][0]),
        //    y: Object.values(conditions['swellings'][0]),
        //    type: 'bar',
        //    name: navigator.language === 'sk' ? 'Opuchy' : 'Swellings',
        //}
        //: null;
//
        //var plotExercise = conditions['exercise'].length > 0 ? {
        //    x: Object.keys(conditions['exercise'][0]),
        //    y: Object.values(conditions['exercise'][0]),
        //    type: 'bar',
        //    name: navigator.language === 'sk' ? 'Tolerancia fyzickej námahy' : 'Physical exertion tolerance',
        //}
        //: null;
//
        //var plotDyspnoea = conditions['dyspnoea'].length > 0 ? {
        //    x: Object.keys(conditions['dyspnoea'][0]),
        //    y: Object.values(conditions['dyspnoea'][0]),
        //    type: 'bar',
        //    name: navigator.language === 'sk' ? 'Dýchavičnosť v ľahu' : 'Dyspnoea while lying down',
        //} : null;
//
        //var layout = {
        //    barmode: 'group',
        //    title: {
        //        text: navigator.language === 'sk' ? 'Stav zo dňa ' + conditions['date'] : 'Status from ' + conditions['date'],
        //    },
        //    xaxis: {
        //        title: {
        //            text: navigator.language === 'sk' ? 'Hodnotenie' : 'Rating',
        //        },
        //        tickvals: [1, 2, 3, 4, 5],
        //        ticktext: navigator.language === 'sk' ? ['Veľmi dobré', 'Dobré', 'Stredne', 'Zlé', 'Veľmi zlé'] : ['Very good', 'Good', 'Neutral', 'Bad', 'Very bad']
        //    },
        //    yaxis: {
        //        title: {
        //            text: navigator.language === 'sk' ? 'Počet' : 'Count',
        //        },
        //        autotick: false,
        //        tick0: 0,
        //        dtick: 1,
        //        range: [0, 5],
        //    },
        //    showlegend: true,
        //    legend: {
        //        "orientation": "h",
        //        xanchor: "center",
        //        yanchor: "top",
        //        y: -0.3,
        //        x: 0.5,
        //    },
        //};
//
        //traces = [];
        //if (plotSwellings) {
        //    traces.push(plotSwellings);
        //}
        //if (plotExercise) {
        //    traces.push(plotExercise);
        //}
        //if (plotDyspnoea) {
        //    traces.push(plotDyspnoea);
        //}
        //if (traces.length > 0) { 
        //    Plotly.newPlot('chart-conditions', traces, layout);  
        //}
//
        // set up ecg charts
        chartECG = {!! $chartECG_encoded !!};

        // taken from https://stackoverflow.com/a/8273091
        function range(start, stop, step) {
            if (typeof stop == 'undefined') {
                // one param defined
                stop = start;
                start = 0;
            }

            if (typeof step == 'undefined') {
                step = 1;
            }

            if ((step > 0 && start >= stop) || (step < 0 && start <= stop)) {
                return [];
            }

            var result = [];
            for (var i = start; step > 0 ? i < stop : i > stop; i += step) {
                result.push(i);
            }

            return result;
        };

        // taken and edited from https://jsfiddle.net/76s1px0j/27/
        function generateXgrid(x) {
            var lines = [];
            var counter = 0;
            let length = x.length;
            for (let i = 0; i < length; i += 40) {
                lines.push({
                    type: 'line',
                    xref: 'x',
                    yref: 'paper',
                    x0: x[i],
                    y0: 0,
                    x1: x[i],
                    opacity: 0.7,
                    y1: 1,
                    layer: 'below',
                    line: {
                        color: '#ff000040',
                        width: counter++ % 5 != 0 ? 0.5 : 2,
                    }
                });
            }
            return lines;
        }

        // taken and edited from https://jsfiddle.net/76s1px0j/27/
        function generateYgrid(y) {
            var lines = [];
            var counter = 0;
            let min = Math.min(...y);
            let max = Math.max(...y);
            for (let i = min; i < max; i += 0.1) {
                lines.push({
                    type: 'line',
                    xref: 'paper',
                    yref: 'y',
                    x0: 0,
                    y0: i,
                    x1: 1,
                    opacity: 0.7,
                    y1: i,
                    layer: 'below',
                    line: {
                        color: '#ff000040',
                        width: counter++ % 5 != 0 ? 0.5 : 2,
                    }
                });
            }
            return lines;
        }

        if (chartECG) {
            id = chartECG['id'];
            name = chartECG['name'];
            unit = chartECG['unit'];
            values = chartECG['values'];
            dates = chartECG['dates'];
            datesMs = chartECG['datesMs'];
            date = chartECG['date'];
            eventsP = chartECG['eventsP'];
            eventsB = chartECG['eventsB'];
            eventsT = chartECG['eventsT'];
            eventsAF = chartECG['eventsAF'];

            var datesWithTimezone = []
            for (let d of dates) {
                //let dWithTimezone = new Date(d);
                let dWithTimezone = moment(d).tz("Europe/Bratislava").format('YYYY-MM-DD[T]HH:mm:ss.SSSSSS');
                datesWithTimezone.push(dWithTimezone);
            }

            var plot = {
                x: datesWithTimezone,
                y: values,
                mode: 'lines',
                name: navigator.language === 'sk' ? names_sk[name] : name,
            };

            var grid = [...generateXgrid(datesWithTimezone), ...generateYgrid(values)]

            var shapesEvents = [];
            for (event of eventsP) {
                shapesEvents.push({
                    type: 'rect',
                    xref: 'x',
                    yref: 'paper',
                    x0: moment(event['start']).tz("Europe/Bratislava").format('YYYY-MM-DD[T]HH:mm:ss.SSSSSS'),
                    x1: moment(event['end']).tz("Europe/Bratislava").format('YYYY-MM-DD[T]HH:mm:ss.SSSSSS'),
                    y0: 0,
                    y1: 1,
                    fillcolor: '#b4aee840',
                    line: {
                        width: 0,
                    },
                    layer: 'below',
                });
            }

            for (event of eventsB) {
                shapesEvents.push({
                    type: 'rect',
                    xref: 'x',
                    yref: 'paper',
                    x0: moment(event['start']).tz("Europe/Bratislava").format('YYYY-MM-DD[T]HH:mm:ss.SSSSSS'),
                    x1: moment(event['end']).tz("Europe/Bratislava").format('YYYY-MM-DD[T]HH:mm:ss.SSSSSS'),
                    y0: 0,
                    y1: 1,
                    fillcolor: '#f0c92940',
                    line: {
                        width: 0,
                    },
                    layer: 'below',
                });
            }

            for (event of eventsT) {
                shapesEvents.push({
                    type: 'rect',
                    xref: 'x',
                    yref: 'paper',
                    x0: moment(event['start']).tz("Europe/Bratislava").format('YYYY-MM-DD[T]HH:mm:ss.SSSSSS'),
                    x1: moment(event['end']).tz("Europe/Bratislava").format('YYYY-MM-DD[T]HH:mm:ss.SSSSSS'),
                    y0: 0,
                    y1: 1,
                    fillcolor: '#f3918940',
                    line: {
                        width: 0,
                    },
                    layer: 'below',
                });
            }

            for (event of eventsAF) {
                shapesEvents.push({
                    type: 'rect',
                    xref: 'x',
                    yref: 'paper',
                    x0: moment(event['start']).tz("Europe/Bratislava").format('YYYY-MM-DD[T]HH:mm:ss.SSSSSS'),
                    x1: moment(event['end']).tz("Europe/Bratislava").format('YYYY-MM-DD[T]HH:mm:ss.SSSSSS'),
                    y0: 0,
                    y1: 1,
                    fillcolor: '#04658240',
                    line: {
                        width: 0,
                    },
                    layer: 'below',
                });
            }

            var layout = {
                title: {
                    text: navigator.language === 'sk' ? names_sk[name] + ' zo dňa ' + date : name + ' from ' + date,
                },
                height: 800,
                xaxis: {
                    title: {
                        text: navigator.language === 'sk' ? 'Čas merania' : 'Time of the measurement (s)',
                    },
                    showgrid: false,
                    range: [datesWithTimezone[14000], datesWithTimezone[17000]],
                },
                yaxis: {
                    title: {
                        text: navigator.language === 'sk' ? 'Hodnota (' + units_sk[unit] + ')' : 'Value (' + unit +
                            ')',
                    },
                    showgrid: false,
                },
                shapes: [...shapesEvents, ...grid],
                showlegend: true,
                legend: {
                    orientation: 'h',
                    xanchor: 'center',
                    yanchor: 'top',
                    y: -0.1,
                    x: 0.5,
                },
            };

            Plotly.newPlot('chart-ecg', [plot], layout);

            // Markers taken and edited from https://jsfiddle.net/76s1px0j/27/
            var myPlot = document.getElementById('chart-ecg')
            var markers = [];
            var markerInfo = [];

            // (register to plotly event click) -> adds Marker on click
            myPlot.on('plotly_click', function(data) {
                var pointX = data.points[0].x
                markerInfo.push(pointX);
                markers.push({
                    type: 'line',
                    xref: 'x',
                    yref: 'paper',
                    x0: pointX,
                    y0: 0,
                    x1: pointX,
                    opacity: 1,
                    y1: 1,
                    line: {
                        color: 'green',
                        width: 3,
                        dash: 'dash',
                    },
                })

                if (markers.length > 6) {
                    markers.shift();
                    markerInfo.shift();
                }

                layout.shapes.push(...markers);

                // update plot with new layout (with markers)
                Plotly.redraw(myPlot, window.data, layout);

                // print marker info 
                $('#marker1')[0].innerText = markerInfo[0] ? (markerInfo[0]) : '--';
                $('#marker2')[0].innerText = markerInfo[1] ? (markerInfo[1]) : '--';
                if (markerInfo[0] && markerInfo[1]) {
                    let a = new Date(markerInfo[0]);
                    let b = new Date(markerInfo[1]);
                    $('#markerResult1')[0].innerText = b - a + ' ms';
                }

                $('#marker3')[0].innerText = markerInfo[2] ? (markerInfo[2]) : '--';
                $('#marker4')[0].innerText = markerInfo[3] ? (markerInfo[3]) : '--';
                if (markerInfo[2] && markerInfo[3]) {
                    let a = new Date(markerInfo[2]);
                    let b = new Date(markerInfo[3]);
                    $('#markerResult2')[0].innerText = b - a + ' ms';
                }

                $('#marker5')[0].innerText = markerInfo[4] ? (markerInfo[4]) : '--';
                $('#marker6')[0].innerText = markerInfo[5] ? (markerInfo[5]) : '--';
                if (markerInfo[4] && markerInfo[5]) {
                    let a = new Date(markerInfo[4]);
                    let b = new Date(markerInfo[5]);
                    $('#markerResult3')[0].innerText = b - a + ' ms';
                }
            });
        }

    </script>

@endsection

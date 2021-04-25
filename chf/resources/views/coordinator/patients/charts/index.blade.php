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
</style>

<div class="container">
    <h1>
        Patients
    </h1>

    <h2>
        {{ $patient['name'].' '.$patient['surname'] }}
    </h2>

    <ul class="nav nav-tabs my-4">
        <li class="nav-item">
            <a class="{{ Request::is('*/profile*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/profile'}}">Profile</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/therapy*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/therapy'}}">Therapy</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/measurements*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/measurements'}}">Measurements</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/charts*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/charts'}}">Charts</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/contacts*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/contacts'}}">Contact</a>
        </li>
    </ul>

    <div id="filter" class="py-3">
        <div class="container px-0">
            <h3>
                Filter
            </h3>

            <div x-data="selectFilter()">
                <form method="POST" action="charts/filter">
                    @csrf
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            Plot data from last
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio1" value="1" x-bind:checked="select1">
                            <label class="form-check-label" for="inlineRadio1">week</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio2" value="2" x-bind:checked="select2">
                            <label class="form-check-label" for="inlineRadio2">month</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio3" value="3" x-bind:checked="select3">
                            <label class="form-check-label" for="inlineRadio3">three months</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio4" value="4" x-bind:checked="select4">
                            <label class="form-check-label" for="inlineRadio4">six months</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio5" value="5" x-bind:checked="select5">
                            <label class="form-check-label" for="inlineRadio5">all time data</label>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-outline-secondary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <h3>
        Charts
    </h3>  

    @foreach($charts as $chart)
    <div id="{{ 'chart-'.$chart['name'] }}" class="mt-5">
    </div>
    @endforeach

    @if(count($chartsECG) > 0 )
    @foreach ($chartsECG as $chart)
    @if ($chart)
    <div class="d-flex justify-content-center pt-5 mt-5 bg-white">
        <div>
            <table>
                <tr>
                    <td class="font-weight-bold pr-2">
                        Marker 1:
                    </td>
                    <td id="marker1">
                        --
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold pr-2">
                        Marker 2:
                    </td>
                    <td id="marker2">
                        --
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold pr-2">
                        Difference:
                    </td>
                    <td id="markerResult">
                        --
                    </td>
                </tr>
            </table>
        </div>
     </div>
    <div class="ecg-chart" id="{{ 'chart-ecg-'.$chart['id'] }}">
        <div class="d-flex align-items-center justify-content-between pr-5 pb-5 pl-5 mb-5 bg-white">
            @if(count($chart['eventsP']) > 0 )
            <div class="d-flex align-items-center">
                <div class="mr-2" style="width: 30px; height: 30px; background-color: #b4aee880;">
                </div>
                <div>
                    Pause detected
                </div>
            </div>
            @endif

            @if(count($chart['eventsB']) > 0 )
            <div class="d-flex align-items-center">
                <div class="mr-2" style="width: 30px; height: 30px; background-color: #f0c92980;">
                </div>
                <div>
                    Bradycardia detected
                </div>
            </div>
            @endif

            @if(count($chart['eventsT']) > 0 )
            <div class="d-flex align-items-center">
                <div class="mr-2" style="width: 30px; height: 30px; background-color: #f3918980;">
                </div>
                <div>
                    Tachycardia detected
                </div>
            </div>
            @endif

            @if(count($chart['eventsAF']) > 0 )
            <div class="d-flex align-items-center">
                <div class="mr-2" style="width: 30px; height: 30px; background-color: #04658280;">
                </div>
                <div>
                    Atrial fibrillation detected
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
    @endforeach
    @endif
</div>

<script>
    // make filter sticky
    var filter = $('#filter')[0];
    var sticky = filter.offsetTop;

    window.onscroll = () => {
        if (window.pageYOffset > sticky) {
            filter.classList.add("sticky");
        } else {
            filter.classList.remove("sticky");
        }
    };

    // select default radio from filter options
    option = {!!$filterOption!!};

    function selectFilter() {
        return {
            select1: option == 1,
            select2: option == 2,
            select3: option == 3,
            select4: option == 4,
            select5: option == 5,
        };
    }

    // set up charts
    charts = {!!$charts_encoded!!};

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
            name: name,
        };

        var lower_threshold_therapeutic = min_therapeutic
        ? {
            x: dates,
            y: Array(dates.length).fill(min_therapeutic),
            mode: 'lines',
            line: {
                dash: 'dot',
            },
            name: 'Lower therapeutic threshold',
        }
        : null;

        var upper_threshold_therapeutic = max_therapeutic
        ? {
            x: dates,
            y: Array(dates.length).fill(max_therapeutic),
            mode: 'lines',
            line: {
                dash: 'dot',
            },
            name: 'Upper therapeutic threshold',
        }
        : null;

        var upper_threshold_safety = max_safety
        ? {
            x: dates,
            y: Array(dates.length).fill(max_safety),
            mode: 'lines',
            line: {
                dash: 'dot',
            },
            name: 'Upper safety threshold',
        }
        : null;

        var lower_threshold_safety = min_safety
        ? {
            x: dates,
            y: Array(dates.length).fill(min_safety),
            mode: 'lines',
            line: {
                dash: 'dot',
            },
            name: 'Lower safety threshold',
        }
        : null;

        var layout = {
            title: {
                text: name,
            },
            xaxis: {
                title: {
                    text: 'Date',
                },
            },
            yaxis: {
                title: {
                    text: 'Value (' + unit + ')',
                },
            },
            showlegend: true,
            legend: {
                "orientation": "h",
                xanchor:"center",
                yanchor:"top",
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

    // set up ecg charts
    chartsECG = {!!$chartsECG_encoded!!};

    for (chart of chartsECG) {
        id = chart['id'];
        name = chart['name'];
        unit = chart['unit'];
        values = chart['values'];
        dates = chart['dates'];
        eventsP = chart['eventsP'];
        eventsB = chart['eventsB'];
        eventsT = chart['eventsT'];
        eventsAF = chart['eventsAF'];

        console.log(values.length);
        var plot = {
            x: dates,
            y: values,
            type: 'scatter',
            name: name,
        };

        var shapes = [];
        for (event of eventsP) {
            shapes.push({
                type: 'rect',
                xref: 'x',
                yref: 'paper',
                x0: event,
                x1: event + 1,
                y0: 0,
                y1: 1,
                fillcolor: '#b4aee880',
                line: {
                    width: 0,
                },
                layer:'below',
            });
        }

        for (event of eventsB) {
            shapes.push({
                type: 'rect',
                xref: 'x',
                yref: 'paper',
                x0: event,
                x1: event + 1,
                y0: 0,
                y1: 1,
                fillcolor: '#f0c92980',
                line: {
                    width: 0,
                },
                layer:'below',
            });
        }

        for (event of eventsT) {
            shapes.push({
                type: 'rect',
                xref: 'x',
                yref: 'paper',
                x0: event,
                x1: event + 1,
                y0: 0,
                y1: 1,
                fillcolor: '#f3918980',
                line: {
                    width: 0,
                },
                layer:'below',
            });
        }

        for (event of eventsAF) {
            shapes.push({
                type: 'rect',
                xref: 'x',
                yref: 'paper',
                x0: event,
                x1: event + 1,
                y0: 0,
                y1: 1,
                fillcolor: '#04658280',
                line: {
                    width: 0,
                },
                layer:'below',
            });
        }

        var layout = {
            title: {
                text: name,
            },
            height: 1000,
            xaxis: {
                title: {
                    text: 'Miliseconds',
                },
                autotick: false,
                tick0: dates[0],
                dtick: 40,
                showgrid: true,
                gridcolor: '#ff000020',
            },
            yaxis: {
                title: {
                    text: 'Value (' + unit + ')',
                },
                autotick: false,
                tick0: -10,
                dtick: 1,
                showgrid: true,
                gridcolor: '#ff000020',
            },
            shapes: shapes,
            showlegend: true,
            legend: {
                orientation: 'h',
                xanchor: 'center',
                yanchor:'top',
                y: -0.1,
                x: 0.5, 
                },
        };

        traces = [plot];

        Plotly.newPlot('chart-ecg-' + id, traces, layout);

        // Markers 
        var myPlot = document.getElementById('chart-ecg-' + id)
        var markers = [];
        var markerInfo = [];
        var marker1Div = document.getElementById('marker1')
        var marker2Div = document.getElementById('marker2')
        var markerResultDiv = document.getElementById('markerResult')


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

        if (markers.length > 2) {
          markers.shift();
          markerInfo.shift();
        }

        layout.shapes.push(...markers);

        // update plot with new layout (with markers)
        Plotly.redraw(myPlot, window.data, layout);

        // print marker info 
        $('#marker1')[0].innerText = markerInfo[0] ? (markerInfo[0] + ' ms') : '--';
        $('#marker2')[0].innerText = markerInfo[1] ? (markerInfo[1] + ' ms') : '--';
        $('#markerResult')[0].innerText = (markerInfo[1] != undefined && markerInfo[0] != undefined) ? ((markerInfo[1] - markerInfo[0]) + ' ms') : '--';
        });

    }

</script>

@endsection

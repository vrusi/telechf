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
        {{ __('Charts') }}
    </h1>
    <p>
        {{ __('Here you can see your measurement data in charts.') }}
    </p>

    <div id="filter" class="py-3">
        <div class="container px-0">
            <h3>
                {{ __('Filter') }}
            </h3>

            <div x-data="selectFilter()">
                <form method="POST" action="charts/filter">
                    @csrf
                    <div class="d-lg-none">
                        <div class="row">
                            <div class="col-12">
                                <div class="mr-3">
                                    {{ __('Plot data from last') }}
                                </div>

                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio1" value="1" x-bind:checked="select1">
                                    <label class="form-check-label" for="inlineRadio1">{{ __('week ') }}</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio2" value="2" x-bind:checked="select2">
                                    <label class="form-check-label" for="inlineRadio2">{{ __('month ') }}</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio3" value="3" x-bind:checked="select3">
                                    <label class="form-check-label" for="inlineRadio3">{{ __('three months') }}</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio3" value="4" x-bind:checked="select4">
                                    <label class="form-check-label" for="inlineRadio3">{{ __('six months') }}</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio3" value="5" x-bind:checked="select5">
                                    <label class="form-check-label" for="inlineRadio3">{{ __('all time data') }}</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div>
                                    <button type="submit" class="btn btn-outline-secondary">{{ __('Submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-none d-lg-block">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                {{ __('Plot data from last') }}
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio1" value="1" x-bind:checked="select1">
                                <label class="form-check-label" for="inlineRadio1">{{ __('week ') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio2" value="2" x-bind:checked="select2">
                                <label class="form-check-label" for="inlineRadio2">{{ __('month ') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio3" value="3" x-bind:checked="select3">
                                <label class="form-check-label" for="inlineRadio3">{{ __('three months') }}</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio3" value="4" x-bind:checked="select4">
                                <label class="form-check-label" for="inlineRadio3">{{ __('six months') }}</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio3" value="5" x-bind:checked="select5">
                                <label class="form-check-label" for="inlineRadio3">{{ __('all time data') }}</label>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-outline-secondary">{{ __('Plot') }}</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    @foreach($charts as $chart)
    <div id="{{ 'chart-'.$chart['name'] }}" class="mb-5">
    </div>
    @endforeach
    
    @if(count($chartsECG) > 0 )
    @foreach ($chartsECG as $chart)
    @if ($chart)
    <div class="ecg-chart my-5" id="{{ 'chart-ecg-'.$chart['id'] }}">
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
            select1: option == 1
            , select2: option == 2
            , select3: option == 3
            , select4: option == 4
            , select5: option == 5
        , }
    }

    // set up charts
    charts = {!!$charts_encoded!!};
    
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
            name: navigator.language === 'sk' ? 'Spodná cieľová hodnota' : 'Lower goal value',
        } : null;

        var upper_threshold_therapeutic = max_therapeutic ? {
            x: dates,
            y: Array(dates.length).fill(max_therapeutic),
            mode: 'lines',
            line: {
                dash: 'dot',
            },
            name: navigator.language === 'sk' ? 'Horná cieľová hodnota' : 'Upper goal value',
        } : null;

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
        
        var plot = {
            x: dates,
            y: values,
            type: 'scatter',
            name: navigator.language === 'sk' ? names_sk[name] : name,
        };

        var layout = {
            title: {
                text: navigator.language === 'sk' ? names_sk[name] : name,
            },
            height: 1000,
            xaxis: {
                title: {
                    text: navigator.language === 'sk' ? 'Čas v ms' :'Time in ms',
                },
                autotick: false,
                tick0: dates[0],
                dtick: 40,
                showgrid: true,
                gridcolor: '#ff000020',
            },

            yaxis: {
                title: {
                    text: navigator.language === 'sk' ? 'Hodnota (' + units_sk[unit] + ')' : 'Value (' + unit + ')',
                },
                autotick: false,
                tick0: -10,
                dtick: 1,
                showgrid: true,
                gridcolor: '#ff000020',
            },
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
    }

</script>

@endsection

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


    @if ($chart['pauseEvent'] || $chart['bradycardia'] || $chart['tachycardia'] || $chart['atrialFibrillation'])
    <div class="border border-danger p-3 mb-5">
        This measurement signalled the presence of:
        <ul>
            @if ($chart['pauseEvent'])
            <li>
                Pause event
            </li>
            @endif
            
            @if ($chart['bradycardia'])
            <li>
                Bradycardia
            </li>
            @endif
            
            @if ($chart['tachycardia'])
            <li>
                Tachycardia
            </li>
            @endif
            
            @if ($chart['atrialFibrillation'])
            <li>
                Atrial fibrillation
            </li>
            @endif
        </ul>
    </div>  
    @endif
    @endforeach
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
            x: dates
            , y: values
            , type: 'scatter'
            , name: name
            , showlegend: true
        , };

        var lower_threshold_therapeutic = min_therapeutic ? {
            x: dates
            , y: Array(dates.length).fill(min_therapeutic)
            , type: 'scatter'

            , line: {
                dash: 'dot'
            , }
            , name: 'Lower therapeutic threshold'
            , showlegend: true
        , } : null;

        var upper_threshold_therapeutic = max_therapeutic ? {
            x: dates
            , y: Array(dates.length).fill(max_therapeutic)
            , type: 'scatter'

            , line: {
                dash: 'dot'
            , }
            , name: 'Upper therapeutic threshold'
            , showlegend: true
        , } : null;

        var upper_threshold_safety = max_safety ? {
            x: dates
            , y: Array(dates.length).fill(max_safety)
            , type: 'scatter'

            , line: {
                dash: 'dot'
            , }
            , name: 'Upper safety threshold'
            , showlegend: true
        , } : null;

        var lower_threshold_safety = min_safety ? {
            x: dates
            , y: Array(dates.length).fill(min_safety)
            , type: 'scatter'

            , line: {
                dash: 'dot'
            , }
            , name: 'Lower safety threshold'
            , showlegend: true
        , } : null;

        var layout = {
            title: {
                text: name,
            },

            height: 1000,

            xaxis: {
                title: {
                    text: 'Date'
                }
            },

            yaxis: {
                title: {
                    text: 'Value (' + unit + ')'
                , }
            }
        , }

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

</script>

@endsection

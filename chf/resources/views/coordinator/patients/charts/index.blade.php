@extends('layouts.app')

@section('content')
<div class="container">
    <h1>
        Patients
    </h1>

    <h2>
        {{ $patient['name'].' '.$patient['surname'] }}
    </h2>

    <ul class="nav nav-tabs my-4">
        <li class="nav-item">
            <a class="{{ Request::is('*/profile') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/profile'}}">Profile</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/therapy') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/therapy'}}">Therapy</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/measurements') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/measurements'}}">Measurements</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/charts') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/charts'}}">Charts</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/contacts') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/contacts'}}">Contact</a>
        </li>
    </ul>

    <h3>
        Charts
    </h3>

    @foreach($charts as $chart)
    <div id="{{ 'chart-'.$chart['name'] }}" class="mb-5">
    </div>
    @endforeach
</div>

<script>
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

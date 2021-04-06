@extends('layouts.app')

@section('content')

<div class="container">
    <h1>
        Charts
    </h1>

    <p>
        Here you can see your measurement data in charts. Upper and lower thresholds define the bounds of your goal values.
    </p>

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
        max = chart['max'];
        min = chart['min'];

        var plot = {
            x: dates
            , y: values
            , type: 'scatter'
            , name: name
            , showlegend: true
        , };

        var lower_threshold = min ? {
            x: dates
            , y: Array(dates.length).fill(min)
            , line: {
                dash: 'dot'
            , }
            , name: 'Lower threshold'
            , showlegend: true
        , } : null;

        var upper_threshold = max ? {
            x: dates
            , y: Array(dates.length).fill(max)
            , line: {
                dash: 'dot'
            , }
            , name: 'Upper threshold'
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

        traces = [plot]
        if (lower_threshold) {
            traces.push(lower_threshold);
        }

        if (upper_threshold) {
            traces.push(upper_threshold);
        }

        Plotly.newPlot('chart-' + name, traces, layout);
    }

</script>
@endsection

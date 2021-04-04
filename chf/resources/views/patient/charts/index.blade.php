@extends('layouts.app')

@section('content')

<div class="container">
    <h1>
        Charts
    </h1>

    @foreach($charts as $chart)
    <h2> {{ $chart['name'] }}</h2>
    <div id="{{ 'chart-'.$chart['name'] }}">
    </div>

    @endforeach
</div>


<script>
    charts = {!! $charts_encoded !!};
    
    for (chart of charts) {

        name = chart['name'];
        values = chart['values'];
        dates = chart['dates'];

        var chartObj = {
            x: dates
            , y: values
            , type: 'scatter'
        };

        Plotly.newPlot('chart-' + name, [chartObj]);
    }

</script>
@endsection

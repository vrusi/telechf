@extends('layouts.app')

@section('content')

<div class="container">
    <h1>
        Charts
    </h1>

    @foreach($charts as $chart)
    <div id="{{ 'chart-'.$chart['name'] }}" class="mb-5">
    </div>

    @endforeach
</div>


<script>
    charts = {!! $charts_encoded !!};

    for (chart of charts) {

        name = chart['name'];
        unit = chart['unit'];
        values = chart['values'];
        dates = chart['dates'];

        var plot = {
            x: dates, 
            y: values,
            type: 'scatter',
        };

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
                    text: 'Value (' + unit + ')',
                }
            },
        }

        Plotly.newPlot('chart-' + name, [plot], layout);
    }

</script>
@endsection

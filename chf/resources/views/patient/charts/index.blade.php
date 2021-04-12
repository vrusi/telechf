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
        Charts
    </h1>
    <p>
        Here you can see your measurement data in charts. Upper and lower thresholds define the bounds of your goal values.
    </p>

    <div id="filter" class="py-3">
        <div class="container px-0">
            <h3>
                Filter
            </h3>

            <div x-data="selectFilter()">
                <form method="POST" action="charts/filter">
                    @csrf
                    <div class="d-lg-none">
                        <div class="row">
                            <div class="col-12">
                                <div class="mr-3">
                                    Plot data from last
                                </div>

                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio1" value="1" x-bind:checked="select1">
                                    <label class="form-check-label" for="inlineRadio1">week</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio2" value="2" x-bind:checked="select2">
                                    <label class="form-check-label" for="inlineRadio2">month</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio3" value="3" x-bind:checked="select3">
                                    <label class="form-check-label" for="inlineRadio3">three months</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio3" value="4" x-bind:checked="select4">
                                    <label class="form-check-label" for="inlineRadio3">six months</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio3" value="5" x-bind:checked="select5">
                                    <label class="form-check-label" for="inlineRadio3">all time data</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div>
                                    <button type="submit" class="btn btn-outline-secondary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-none d-lg-block">
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
                                <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio3" value="4" x-bind:checked="select4">
                                <label class="form-check-label" for="inlineRadio3">six months</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="filterOption" id="inlineRadio3" value="5" x-bind:checked="select5">
                                <label class="form-check-label" for="inlineRadio3">all time data</label>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-outline-secondary">Submit</button>
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

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="py-12" x-data="{ tab: 'alarms' }">


        <div class="flex">
            <button @click="tab='alarms'" :class="{'bg-blue-800': tab == 'alarms'}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Alarms
            </button>
            <button @click="tab='summary'" :class="{'bg-blue-800': tab == 'summary'}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Summary
            </button>
        </div>
        <div class="mt-5">
            <div x-show="tab=='alarms'">
                These measurements you took have triggered alarms
            </div>
            <div x-show="tab=='summary'">
                These are your latest measurements

                <table id="summary-table">
                    <thead>
                        <tr>
                            <th>
                                Date
                            </th>
                            @foreach($parameters as $parameter)
                            <th>
                                {{$parameter['name']}}
                            </th>
                            @endforeach
                            <th>
                                Swellings
                            </th>
                            <th>

                                Exercise Tolerance
                            </th>
                            <th>
                                Nocturnal Dyspnoea
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($summary as $date => $day)
                        <tr>
                            <td>
                                {{$date}}
                            </td>
                            @foreach($day as $parameter)
                            <td>
                                {{$parameter['value'] ?? '--' }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>


    </div>
</div>

</div>

<script>
    $(document).ready(function() {
        $.noConflict();
        $('#summary-table').DataTable();
    });

</script>
@endsection

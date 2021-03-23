<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ tab: 'alarms' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
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

                            <table id="table">
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
                                        <td style="background: #ffd1d1">
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
    </div>


</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New measurement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                @if(!empty($takeToday))
                <div>
                    <h3 class="font-semibold text-xl text-gray-800 leading-tight">
                        To take today
                    </h3>
                    @foreach($takeToday as $parameter)
                    <div class="m-5">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <a href="{{ url('/measurements/create/'.$parameter->id) }}">
                                {{ $parameter->name }}
                            </a>
                        </button>
                    </div>

                    @endforeach
                </div>
                @endif

                @if(!empty($takeThisWeek))
                <div>
                    <h3 class="font-semibold text-xl text-gray-800 leading-tight">
                        To take this week
                    </h3>
                    @foreach($takeThisWeek as $parameter)
                    <div class="m-5">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <a href="{{ url('/measurements/create/'.$parameter->id) }}">
                                {{ $parameter->name }}
                            </a>
                        </button>
                    </div>

                    @endforeach
                </div>
                @endif

                @if(!empty($extra))
                <div>
                    <h3 class="font-semibold text-xl text-gray-800 leading-tight">
                        Extra measurements
                    </h3>
                    @foreach($extra as $parameter)
                    <div class="m-5">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <a href="{{ url('/measurements/create/'.$parameter['id']) }}">
                                {{ $parameter['name'] }}
                            </a>
                        </button>
                    </div>

                    @endforeach
                </div>
                @endif

                <div class="p-6 bg-white border-b border-gray-200">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <a href="{{ url('/measurements') }}">
                            Cancel
                        </a>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

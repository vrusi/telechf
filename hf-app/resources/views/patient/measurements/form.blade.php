<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your measurements') }}
        </h2>
    </x-slot>

    <div x-data="{ instructionsRead: false }">
        <div x-show="!instructionsRead">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div>
                            {!! $parameter->instructions !!}
                        </div>


                        <div class="p-6 bg-white border-b border-gray-200">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <a href="{{ url('/measurements/create') }}">
                                    Back
                                </a>
                            </button>
                        </div>

                        <div class="p-6 bg-white border-b border-gray-200">
                            <button @click="instructionsRead = !instructionsRead" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Continue
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div x-show="instructionsRead">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                        <form method="POST" action="/measurements" class="m-5">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li class="text-red-500">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @csrf
                            <input type="hidden" name="parameter_id" value="{{ $parameter->id }}">
                            <label class="block">
                                <span class="text-gray-700">{{ $parameter->name }}</span>
                                <input required type="number" name="value" class="mt-1 block w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0" placeholder="">
                                <span class="text-gray-700">{{ $parameter->unit }}</span>
                            </label>

                            <label class="block mt-5">
                                <span class="text-gray-700">Rate your swellings on a scale</span>
                                <select required name="swellings" class="block w-full mt-1 rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0">
                                    <option value="1">Very good</option>
                                    <option value="2">Good</option>
                                    <option value="3" selected>Neutral</option>
                                    <option value="4">Bad</option>
                                    <option value="5">Very bad</option>
                                </select>
                            </label>

                            <label class="block mt-5">
                                <span class="text-gray-700">Rate your physical exertion tolerance</span>
                                <select required name="exercise_tolerance" class="block w-full mt-1 rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0">
                                    <option value="1">Very good</option>
                                    <option value="2">Good</option>
                                    <option value="3" selected>Neutral</option>
                                    <option value="4">Bad</option>
                                    <option value="5">Very bad</option>
                                </select>
                            </label>

                            <label class="block mt-5">
                                <span class="text-gray-700">Rate your sleeping breathlessness</span>
                                <select required name="dyspnoea" class="block w-full mt-1 rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0">
                                    <option value="1">Very good</option>
                                    <option value="2">Good</option>
                                    <option value="3" selected>Neutral</option>
                                    <option value="4">Bad</option>
                                    <option value="5">Very bad</option>
                                </select>
                            </label>
                            <div class="flex">
                                <div class="p-6 bg-white">
                                    <button @click="instructionsRead = !instructionsRead" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Back
                                    </button>
                                </div>

                                <div class="p-6 bg-white">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Finish
                                    </button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>

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
                        <div>
                            {{$parameter->name}} form
                        </div> 
                        <div class="p-6 bg-white border-b border-gray-200">
                            <button @click="instructionsRead = !instructionsRead" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Back
                            </button>
                        </div>

                        <div class="p-6 bg-white border-b border-gray-200">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Finish
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>

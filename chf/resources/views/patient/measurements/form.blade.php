@extends('layouts.app')

@section('content')
<div class="container patient">
    <div x-data="{ instructionsRead: false }">
        <div x-show="!instructionsRead">

            <div class="text-justify">
                {!! $parameter->instructions !!}
            </div>


            <div class="row pt-5">
                <div class="col">
                    <div class="d-flex d-md-none flex-row fixed-bottom">
                        <a class="btn btn-primary w-50 rounded-0" href="{{ url('/measurements/create') }}">Back</a>
                        <button class="btn btn-primary w-50 rounded-0" @click="instructionsRead = !instructionsRead">Continue</button>
                    </div>


                    <div class="d-none d-md-flex justify-content-center">
                        <a class="btn btn-secondary mr-3 w-50" href="{{ url('/measurements/create') }}">Back</a>
                        <button class="btn btn-primary w-50" @click="instructionsRead = !instructionsRead">Continue</button>
                    </div>

                </div>
            </div>
        </div>

        <div x-show="instructionsRead">

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
                <div class="mb-5">
                    <input type="hidden" name="parameter_id" value="{{ $parameter->id }}">

                    <div class="form-group">
                        <label for="value">{{ $parameter->name }} ({{ $parameter->unit }})</label>
                        <input required type="number" class="form-control" id="value" placeholder="Enter your measurement">
                    </div>

                    <div class="form-group">
                        <label class="mt-5">
                            Rate your swellings
                        </label>
                        <select required name="swellings" class="form-control">
                            <option value="1">Very good</option>
                            <option value="2">Good</option>
                            <option value="3" selected>Neutral</option>
                            <option value="4">Bad</option>
                            <option value="5">Very bad</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="mt-5">
                            Rate your physical exertion tolerance
                        </label>
                        <select required name="exercise_tolerance" class="form-control">
                            <option value="1">Very good</option>
                            <option value="2">Good</option>
                            <option value="3" selected>Neutral</option>
                            <option value="4">Bad</option>
                            <option value="5">Very bad</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label class="mt-5">
                            Rate your sleeping breathlessness
                        </label>
                        <select required name="dyspnoea" class="form-control">
                            <option value="1">Very good</option>
                            <option value="2">Good</option>
                            <option value="3" selected>Neutral</option>
                            <option value="4">Bad</option>
                            <option value="5">Very bad</option>
                        </select>
                    </div>
                </div>


                <div class="row pt-5">
                    <div class="col">
                        <div class="d-flex d-md-none flex-row fixed-bottom">
                            <button class="btn btn-primary w-50 rounded-0" @click="instructionsRead = !instructionsRead"">Back</button>
                            <a class=" btn btn-primary w-50 rounded-0" href="{{ url('/measurements/') }}">Finish</a>
                        </div>

                        <div class="d-none d-md-flex justify-content-center">
                            <button class="btn btn-secondary mr-3 w-50" @click="instructionsRead = !instructionsRead"">Back</button>
                            <a class=" btn btn-primary w-50" href="{{ url('/measurements/') }}">Finish</a>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

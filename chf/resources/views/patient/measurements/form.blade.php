@extends('layouts.app')

@section('content')

    <style>
        .instructions {
            max-width: 300px;
            position: absolute;
            left: 5vw;
            padding: 0 1rem;
        }

        @media(min-width:1400px) {
            .instructions {
                max-width: 400px;
            }
        }

        @media(min-width:1650px) {
            .instructions {
                max-width: 500px;
            }
        }

        @media(min-width:2000px) {
            .instructions {
                left: 10vw;
            }
        }

        @media(min-width:2300px) {
            .instructions {
                left: 15vw;
            }
        }

        @media(min-width:2300px) {
            .instructions {
                left: 15vw;
            }
        }

    </style>

    @if(strtolower($parameter->name) != 'ecg')
    <div class="d-none d-xl-block instructions">
        <h2 class="pb-3">
            {{ __('Instructions') }}
        </h2>
        <div class="text-justify">
            @if ($locale == 'en')
                {!! $parameter->instructions_en !!}
            @elseif ($locale == 'sk')
                {!! $parameter->instructions_sk !!}
            @endif
        </div>
    </div>
    @endif

    <div class="container patient">
        <h1 class="pb-3">
            {{ __('Measurement form') }}
        </h1>

        @if ($extra)
            <h2>
                {{ __($parameter->name) }} - {{ __('extra measurement') }}
            </h2>
        @else
            <h2>
                {{ __($parameter->name) }}
            </h2>
        @endif

        @if(strtolower($parameter->name) == 'ecg')
            {{ __('Please use the mobile application to upload your ECG data.') }}

            <div class="d-flex justify-content-center">
                <a class="btn btn-secondary w-50 my-5" href="{{ route('measurements.create') }}">
                    {{ __('Back') }}
                </a>
            </div>
        @else

            <div class="d-flex justify-content-center d-xl-none" x-data="{ instructionsOpen: false }">
                <div @click="instructionsOpen=!instructionsOpen" class="d-flex" data-toggle="collapse"
                    data-target="#instructions" aria-expanded="false" aria-controls="instructions">
                    <button class="btn btn-outline-secondary">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="mr-3 text-justify">
                                {{ __('Tap to read instructions') }}
                            </div>

                            <i x-show="!instructionsOpen" class="fas fa-caret-down"></i>
                            <i x-show="instructionsOpen" class="fas fa-caret-up"></i>
                        </div>
                    </button>

                </div>

                <div class="collapse" id="instructions">
                    <div class="card card-body text-justify">
                        @if ($locale == 'en')
                            {!! $parameter->instructions_en !!}
                        @elseif ($locale == 'sk')
                            {!! $parameter->instructions_sk !!}
                        @endif
                    </div>
                </div>
            </div>

            @php
                $extra = Request::query('extra');
            @endphp

            @if ($extra)
                <form method="POST" action="/measurements?extra=1" class="m-5">
                @else
                    <form method="POST" action="/measurements" class="m-5">
            @endif

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
                    <label for="value">{{ __($parameter->name) }} ({{ __($parameter->unit) }})</label>
                    <input required type="number" step="0.01" class="form-control" name="value" id="value"
                        placeholder="{{ __('Enter your measurement') }}">
                </div>

                <div class="form-group">
                    <label class="mt-3">
                        {{ __('Rate your swellings') }}
                    </label>
                    <select required name="swellings" class="form-control">
                        <option value="1">{{ __('Very good') }}</option>
                        <option value="2">{{ __('Good') }}</option>
                        <option value="3" selected>{{ __('Neutral') }}</option>
                        <option value="4">{{ __('Bad') }}</option>
                        <option value="5">{{ __('Very bad') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="mt-3">
                        {{ __('Rate your physical exertion tolerance') }}
                    </label>
                    <select required name="exercise_tolerance" class="form-control">
                        <option value="1"> {{ __('Very good') }} </option>
                        <option value="2">{{ __('Good') }}</option>
                        <option value="3" selected>{{ __('Neutral') }}</option>
                        <option value="4">{{ __('Bad') }}</option>
                        <option value="5">{{ __('Very bad') }}</option>
                    </select>
                </div>


                <div class="form-group">
                    <label class="mt-3">
                        {{ __('Rate your sleeping breathlessness') }}
                    </label>
                    <select required name="dyspnoea" class="form-control">
                        <option value="1"> {{ __('Very good') }} </option>
                        <option value="2">{{ __('Good') }}</option>
                        <option value="3" selected>{{ __('Neutral') }}</option>
                        <option value="4">{{ __('Bad') }}</option>
                        <option value="5">{{ __('Very bad') }}</option>
                    </select>
                </div>

                @if ($extra)
                    <div class="form-group">
                        <label for="extraDescription">{{ __('Why did you take the extra measurement?') }}</label>
                        <textarea class="form-control" id="extraDescription" name="extraDescription" rows="3"
                            placeholder="{{ __("Describe how you're feeling") }}"></textarea>
                    </div>
                @endif
            </div>

            <div class="row pt-5">
                <div class="col">
                    <div class="d-flex d-md-none flex-row fixed-bottom">
                        <a class="btn btn-secondary w-50 rounded-0" href="{{ route('measurements.create') }}">
                            {{ __('Back') }}
                        </a>
                        <button type="submit" class="btn btn-primary w-50 rounded-0">
                            {{ __('Finish') }}
                        </button>
                    </div>

                    <div class="d-none d-md-flex justify-content-center">
                        <a class="btn btn-secondary mr-3 w-50" href="{{ route('measurements.create') }}">
                            {{ __('Back') }}
                        </a>
                        <button type="submit" class="btn btn-primary w-50">
                            {{ __('Finish') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif

        </form>
    </div>
@endsection

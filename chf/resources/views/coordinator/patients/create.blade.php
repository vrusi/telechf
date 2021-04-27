@extends('layouts.app')

@section('content')

    <div class="container">

        <h1>{{ __('New patient form') }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('patients.store') }}">
            @csrf
            <div class="row">
                <div class="col-6">
                    <h2>{{ __('Personal information') }}</h2>
                    <div class="row">
                        <div class="col-6">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">{{ __('First name') }}</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="surname">{{ __('Surname') }}</label>
                                    <input type="text" class="form-control" id="surname" name="surname" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="sex">{{ __('Sex') }}</label>
                                    <select id="sex" name="sex" class="form-control" required>
                                        <option value="male" selected>{{ __('male') }}</option>
                                        <option value="female">{{ __('female') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <fieldset>
                                    <legend class="h5">{{ __('Date of birth') }}</legend>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="birthDay">{{ __('Day') }}</label>
                                                <input type="number" class="form-control" id="birthDay" name="birthDay"
                                                    min="1" max="31" required>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="birthMonth">{{ __('Month') }}</label>
                                                <input type="number" class="form-control" id="birthMonth" name="birthMonth"
                                                    min="1" max="12" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="birthYear">{{ __('Year') }}</label>
                                                <input type="number" class="form-control" id="birthYear" name="birthYear"
                                                    min="1900" max="2021" required>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="height">{{ __('Height') }} (cm)</label>
                                    <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$" class="form-control"
                                        id="height" name="height" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="weight">{{ __('Weight') }} (kg)</label>
                                    <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$" class="form-control"
                                        id="weight" name="weight" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="email">{{ __('Email') }}</label>
                                <input type="email" class="form-control" id="email" name="email" value="@" required>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="form-group">
                                    <label for="mobile">{{ __('Mobile') }}</label>
                                    <input type="tel" class="form-control" id="mobile" name="mobile" value="+421">
                                    <small>{{ __('Format:') }} +421000111222</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="password">{{ __('Password') }}</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <small>{{ __('Format:') }} {{ __('no conditions') }}</small>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="mac">{{ __("The MAC address of patient's ECG sensor") }}</label>
                                    <input pattern="^([0-9A-Fa-f]{2}[:]){5}([0-9A-Fa-f]{2})$" type="text"
                                        class="form-control" id="mac" name="mac" required>
                                    <small>{{ __('Format:') }} 01:23:45:67:89:AB</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <h2>{{ __('Therapy information') }}</h2>

                    <div class="parameters">
                        <h3>{{ __('Parameters to monitor') }}</h3>
                        @foreach ($parameters as $parameter)
                            @if ($parameter->fillable)
                                <button class="btn btn-outline-primary mr-1 mt-2" type="button" data-toggle="collapse"
                                    data-target="#{{ 'collapse' . $parameter->id . 'param' }}" aria-expanded="false"
                                    aria-controls="{{ 'collapse' . $parameter->id . 'param' }}">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input"
                                            id="{{ 'parameter' . $parameter->id . 'check' }}"
                                            name="{{ 'parameter' . $parameter->id . 'check' }}">
                                        <label class="form-check-label"
                                            for="{{ 'parameter' . $parameter->id . 'check' }}">{{ __($parameter->name) }}</label>
                                    </div>
                                </button>

                                <div class="collapse" id="{{ 'collapse' . $parameter->id . 'param' }}">
                                    <div class="card card-body">
                                        <h4>
                                            {{ __($parameter->name) }}
                                        </h4>

                                        @if (strtolower($parameter->name) != 'ecg')
                                            <h5>{{ __('Safety thresholds') }}</h5>
                                            <div class="d-flex align-items-center">
                                                <div class="form-group mr-3">
                                                    <label
                                                        for="{{ 'parameter' . $parameter->id . 'MinSafety' }}">{{ __('Lower threshold') }}
                                                        ({{ __($parameter->unit) }})</label>
                                                    <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$"
                                                        class="form-control"
                                                        id="{{ 'parameter' . $parameter->id . 'MinSafety' }}"
                                                        name="{{ 'parameter' . $parameter->id . 'MinSafety' }}">
                                                </div>

                                                <div class="form-group">
                                                    <label
                                                        for="{{ 'parameter' . $parameter->id . 'MaxSafety' }}">{{ __('Upper threshold') }}
                                                        ({{ __($parameter->unit) }})</label>
                                                    <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$"
                                                        class="form-control"
                                                        id="{{ 'parameter' . $parameter->id . 'MaxSafety' }}"
                                                        name="{{ 'parameter' . $parameter->id . 'MaxSafety' }}">
                                                </div>
                                            </div>

                                            <h5>{{ __('Therapeutic thresholds') }}</h5>
                                            <div class="d-flex align-items-center">
                                                <div class="form-group mr-3">
                                                    <label
                                                        for="{{ 'parameter' . $parameter->id . 'MinTherapeutic' }}">{{ __('Lower threshold') }}
                                                        ({{ __($parameter->unit) }})</label>
                                                    <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$"
                                                        class="form-control"
                                                        id="{{ 'parameter' . $parameter->id . 'MinTherapeutic' }}"
                                                        name="{{ 'parameter' . $parameter->id . 'MinTherapeutic' }}">
                                                </div>

                                                <div class="form-group">
                                                    <label
                                                        for="{{ 'parameter' . $parameter->id . 'MaxTherapeutic' }}">{{ __('Upper threshold') }}
                                                        ({{ __($parameter->unit) }})</label>
                                                    <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$"
                                                        class="form-control"
                                                        id="{{ 'parameter' . $parameter->id . 'MaxTherapeutic' }}"
                                                        name="{{ 'parameter' . $parameter->id . 'MaxTherapeutic' }}">
                                                </div>
                                            </div>
                                        @endif

                                        <h5>{{ __('Measurement frequency') }}</h5>
                                        <div class="d-flex align-items-center">

                                            <div class="form-group mr-3">
                                                <label
                                                    for="{{ 'parameter' . $parameter->id . 'times' }}">{{ __('Times') }}</label>
                                                <input type="number" class="form-control"
                                                    id="{{ 'parameter' . $parameter->id . 'times' }}"
                                                    name="{{ 'parameter' . $parameter->id . 'times' }}" placeholder="3">
                                            </div>

                                            <div class="form-group">
                                                <label
                                                    for="{{ 'parameter' . $parameter->id . 'per' }}">{{ __('Per') }}</label>
                                                <select class="form-control"
                                                    id="{{ 'parameter' . $parameter->id . 'per' }}"
                                                    name="{{ 'parameter' . $parameter->id . 'per' }}">
                                                    <option value="">--</option>
                                                    <option value="hour">{{ __('hour') }}</option>
                                                    <option value="day">{{ __('day') }}</option>
                                                    <option value="week" selected>{{ __('week') }}</option>
                                                    <option value="month">{{ __('month') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="recommendations my-3">
                        <h3>{{ __('Recommendations') }}</h3>
                        <div class="form-group">
                            <label
                                for="recommendations">{{ __('Enter some recommendations for the patient to stay healthy') }}</label>
                            <textarea class="form-control" id="recommendations" name="recommendations" rows="3"
                                placeholder="{{ __('Do not smoke. Exercise 3 times a week.') }}"></textarea>
                        </div>
                    </div>

                    <div class="conditions my-3">
                        <h3>{{ __('Conditions') }}</h3>
                        <div class="form-group">
                            <label
                                for="conditions">{{ __('Select which conditions the patient is being treated for') }}</label>
                            <select multiple class="form-control" id="conditions" name="conditions[]">
                                @foreach ($conditions as $condition)
                                    <option value="{{ $condition->id }}">{{ __($condition->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="prescriptions my-3">
                        <h3>{{ __('Drugs') }}</h3>
                        <p>{{ __('Select which drugs the patient is currently prescribed') }}</p>
                        @foreach ($drugs as $drug)
                            <button class="btn btn-outline-primary m-1" type="button" data-toggle="collapse"
                                data-target="#{{ 'collapse' . $drug->id . 'drug' }}" aria-expanded="false"
                                aria-controls="{{ 'collapse' . $drug->id . 'drug' }}">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="{{ 'drug' . $drug->id }}"
                                        name="{{ 'drug' . $drug->id }}">
                                    <label class="form-check-label"
                                        for="{{ 'drug' . $drug->id }}">{{ ucfirst($drug->name) }}</label>
                                </div>
                            </button>

                            <div class="collapse" id="{{ 'collapse' . $drug->id . 'drug' }}">
                                <div class="card card-body">
                                    <h4>
                                        {{ ucfirst($drug->name) }}
                                    </h4>

                                    <h5>{{ __('Dosage') }}</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="form-group mr-3">
                                            <label
                                                for="{{ 'drug' . $drug->id . 'volume' }}">{{ __('Volume') }}</label>
                                            <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$"
                                                class="form-control" id="{{ 'drug' . $drug->id . 'volume' }}"
                                                name="{{ 'drug' . $drug->id . 'volume' }}" placeholder="12.5">
                                        </div>

                                        <div class="form-group">
                                            <label for="{{ 'drug' . $drug->id . 'unit' }}">{{ __('Unit') }}</label>
                                            <input type="text" class="form-control"
                                                id="{{ 'drug' . $drug->id . 'unit' }}"
                                                name="{{ 'drug' . $drug->id . 'unit' }}" placeholder="mg">
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <div class="form-group mr-3">
                                            <label for="{{ 'drug' . $drug->id . 'times' }}">{{ __('Times') }}</label>
                                            <input type="number" class="form-control"
                                                id="{{ 'drug' . $drug->id . 'times' }}"
                                                name="{{ 'drug' . $drug->id . 'times' }}" placeholder="3">
                                        </div>

                                        <div class="form-group">
                                            <label for="{{ 'drug' . $drug->id . 'per' }}">{{ __('Per') }}</label>
                                            <select class="form-control" id="{{ 'drug' . $drug->id . 'per' }}"
                                                name="{{ 'drug' . $drug->id . 'per' }}">
                                                <option value="">--</option>
                                                <option value="hour">{{ __('hour') }}</option>
                                                <option value="day">{{ __('day') }}</option>
                                                <option value="week" selected>{{ __('week') }}</option>
                                                <option value="month">{{ __('month') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <input type="hidden" name="coordinatorId" value="{{ $coordinator->id }}">

            <div class="d-flex my-5 justify-content-center">
                <div class="mr-3">
                    <a href="{{ route('patients.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>

                <div class="ml-3">

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>
                        {{ __('Create patient profile') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>
            {{ __('Patients') }}
        </h1>

        <h3>
            {{ $patient['name'] . ' ' . $patient['surname'] }}
        </h3>

        <ul class="nav nav-tabs my-4">
            <li class="nav-item">
                <a class="{{ Request::is('*/profile*') ? 'nav-link active' : 'nav-link' }}"
                    href="{{ '/coordinator/patients/' . $patient['id'] . '/profile' }}">{{ __('Profile') }}</a>
            </li>
            <li class="nav-item">
                <a class="{{ Request::is('*/therapy*') ? 'nav-link active' : 'nav-link' }}"
                    href="{{ '/coordinator/patients/' . $patient['id'] . '/therapy' }}">{{ __('Therapy') }}</a>
            </li>
            <li class="nav-item">
                <a class="{{ Request::is('*/measurements*') ? 'nav-link active' : 'nav-link' }}"
                    href="{{ '/coordinator/patients/' . $patient['id'] . '/measurements' }}">{{ __('Measurements') }}</a>
            </li>
            <li class="nav-item">
                <a class="{{ Request::is('*/charts*') ? 'nav-link active' : 'nav-link' }}"
                    href="{{ '/coordinator/patients/' . $patient['id'] . '/charts' }}">{{ __('Charts') }}</a>
            </li>
            <li class="nav-item">
                <a class="{{ Request::is('*/contacts*') ? 'nav-link active' : 'nav-link' }}"
                    href="{{ '/coordinator/patients/' . $patient['id'] . '/contacts' }}">{{ __('Contact') }}</a>
            </li>
        </ul>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h3 class="mb-3">
            {{ __('Prescriptions') }}
        </h3>

        <form method="POST" action="{{ route('coordinator.patients.profile.prescriptions.store', $patient['id']) }}">
            @csrf
            <p>{{ __('Select which drugs the patient is currently prescribed') }}</p>
            @foreach ($drugs as $drug)
                <button class="btn btn-outline-primary m-1" type="button" data-toggle="collapse"
                    data-target="#{{ 'collapse' . $drug->id . 'drug' }}" aria-expanded="false"
                    aria-controls="{{ 'collapse' . $drug->id . 'drug' }}">
                    <div class="form-check">

                        @php
                            $isChecked = false;
                            $volume = null;
                            $span = null;
                            $times = null;
                            $unit = null;
                            foreach ($drugsPatient as $drugPatient) {
                                if ($drug->id == $drugPatient->id) {
                                    $isChecked = true;
                                    $volume = $drugPatient->dosage_volume;
                                    $span = $drugPatient->dosage_span;
                                    $times = $drugPatient->dosage_times;
                                    $unit = $drugPatient->dosage_unit;
                                }
                            }
                        @endphp

                        <input type="checkbox" class="form-check-input" id="{{ 'drug' . $drug->id }}"
                            name="{{ 'drug' . $drug->id }}" {{ $isChecked ? 'checked' : '' }}>
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
                                <label for="{{ 'drug' . $drug->id . 'volume' }}">{{ __('Volume') }}</label>
                                <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$" class="form-control"
                                    id="{{ 'drug' . $drug->id . 'volume' }}"
                                    name="{{ 'drug' . $drug->id . 'volume' }}" value={{ $volume }}>
                            </div>

                            <div class="form-group">
                                <label for="{{ 'drug' . $drug->id . 'unit' }}">{{ __('Unit') }}</label>
                                <input type="text" class="form-control" id="{{ 'drug' . $drug->id . 'unit' }}"
                                    name="{{ 'drug' . $drug->id . 'unit' }}" value={{ $unit }}>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="form-group mr-3">
                                <label for="{{ 'drug' . $drug->id . 'times' }}">{{ __('Times') }}</label>
                                <input type="number" class="form-control" id="{{ 'drug' . $drug->id . 'times' }}"
                                    name="{{ 'drug' . $drug->id . 'times' }}" value={{ $times }}>
                            </div>

                            <div class="form-group">
                                <label for="{{ 'drug' . $drug->id . 'per' }}">{{ __('Per') }}</label>
                                <select class="form-control" id="{{ 'drug' . $drug->id . 'per' }}"
                                    name="{{ 'drug' . $drug->id . 'per' }}">
                                    <option value="" {{ !$span ? 'selected' : '' }}>--</option>
                                    <option value="hour" {{ $span == 'hour' ? 'selected' : '' }}>{{ __('hour') }}
                                    </option>
                                    <option value="day" {{ $span == 'day' ? 'selected' : '' }}>{{ __('day') }}
                                    </option>
                                    <option value="week" {{ $span == 'week' ? 'selected' : '' }}>{{ __('week') }}
                                    </option>
                                    <option value="month" {{ $span == 'month' ? 'selected' : '' }}>{{ __('month') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="d-flex align-items-center my-5 justify-content-center">
                <a href="{{ route('coordinator.patients.therapy', ['patient' => $patient->id]) }}"
                    class="btn btn-secondary mr-3">{{ __('Cancel') }}</a>
                <input type="hidden" name="patientId" id="patientId" value="{{ $patient->id }}">
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
@endsection

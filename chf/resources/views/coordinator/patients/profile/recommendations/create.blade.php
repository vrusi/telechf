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
            {{ __('Recommendations') }}
        </h3>

        <form method="POST" action="{{ route('coordinator.patients.profile.recommendations.store', $patient['id']) }}">
            @csrf
            <div class="form-group">
                <label
                    for="recommendations">{{ __('Enter some recommendations for the patient to stay healthy') }}</label>
                <textarea class="form-control" id="recommendations" name="recommendations" rows="3">{{ $patient['recommendations'] ?? null }}</textarea>
            </div>

            <div class="d-flex align-items-center my-5 justify-content-center">
                <a href="{{ route('coordinator.patients.therapy', ['patient' => $patient->id]) }}"
                    class="btn btn-secondary mr-3">{{ __('Cancel') }}</a>
                <input type="hidden" name="patientId" id="patientId" value="{{ $patient->id }}">
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
@endsection

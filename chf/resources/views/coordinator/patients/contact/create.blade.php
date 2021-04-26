@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>
            {{ __('Patients') }}
        </h1>

        <h2>
            {{ $patient['name'] . ' ' . $patient['surname'] }}
        </h2>

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

        <h3 class="my-3">
            {{ __('New contact') }}
        </h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('contacts.store') }}">
            @csrf
            <div class="my-5">
                <h4>{{ __('Add a new contact') }}</h4>
                <div class="d-flex align-items-center">
                    <div class="form-group mr-3">
                        <label for="titles_prefix"> {{ __('Titles before the name') }} </label>
                        <input type="titles_prefix" class="form-control" name="titles_prefix" id="titles_prefix">
                    </div>

                    <div class="form-group mr-3">
                        <label for="name"> {{ __('First name') }} </label>
                        <input type="name" class="form-control" name="name" id="name" required>
                    </div>

                    <div class="form-group mr-3">
                        <label for="surname"> {{ __('Surname') }} </label>
                        <input type="surname" class="form-control" name="surname" id="surname" required>
                    </div>

                    <div class="form-group">
                        <label for="titles_postfix"> {{ __('Titles after the name') }} </label>
                        <input type="titles_postfix" class="form-control" name="titles_postfix" id="titles_postfix">
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="form-group mr-3">
                        <label for="email"> {{ __('Email') }} </label>
                        <input type="text" class="form-control" name="email" id="email" value="@" required>
                    </div>

                    <div class="form-group mr-3">
                        <label for="mobile"> {{ __('Mobile') }} </label>
                        <input type="tel" minlength="13" class="form-control" name="mobile" id="mobile" value="+421" required>
                    </div>

                    <div class="form-group">
                        <label for="type">{{ __('Type') }}</label>
                        <select class="form-control" id="type" name="type">
                            <option value="1">{{ __('General practitioner') }}</option>
                            <option value="2">{{ __('Cardiologist') }}</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex align-items-center my-5 justify-content-center">
                    <input type="hidden" name="patientId" id="patientId" value="{{ $patient->id }}">
                    <button type="submit" class="btn btn-primary">{{ __('Create new contact') }}</button>
                </div>
            </div>
        </form>

        <form method="POST" action="{{ route('contacts.store') }}">
            @csrf
            <div class="my-5">
                <h4>{{ __('or pick an existing one') }}</h4>
                <div class="form-group">
                    <label for="contact">{{ __('Contact') }}</label>
                    <select class="form-control" id="contact" name="contactId">
                        <option value="">--</option>
                        @foreach ($contacts as $contact)
                            <option value="{{ $contact->id }}">
                                {{ $contact->titles_prefix . ' ' . $contact->name . ' ' . $contact->surname . ' ' . $contact->titles_postfix }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex align-items-center my-5 justify-content-center">
                <a href="{{ route('coordinator.patients.contact', ['patient' => $patient->id]) }}"
                    class="btn btn-secondary mr-3">{{ __('Cancel') }}</a>
                <input type="hidden" name="patientId" id="patientId" value="{{ $patient->id }}">
                <button type="submit" class="btn btn-primary">{{ __('Add patient contact') }}</button>
            </div>
        </form>
    </div>


@endsection

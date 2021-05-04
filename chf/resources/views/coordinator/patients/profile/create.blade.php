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
            {{ __('Personal information') }}
        </h3>

        <form method="POST" action="{{ route('coordinator.patients.profile.update', $patient['id']) }}">
            @csrf
            <div class="form-group">
                <label for="name">{{ __('First name') }}</label>
                <input type="text" class="form-control" id="name" name="name" value={{ $patient['name'] }}>
            </div>

            <div class="form-group">
                <label for="surname">{{ __('Surname') }}</label>
                <input type="text" class="form-control" id="surname" name="surname" value={{ $patient['surname'] }}>
            </div>

            <div class="form-group">
                <label for="sex">{{ __('Sex') }}</label>
                <select id="sex" name="sex" class="form-control">
                    <option value="" {{ !$patient['sex'] ? 'selected' : '' }}>--</option>
                    <option value="male" {{ $patient['sex'] == 'male' ? 'selected' : '' }}>{{ __('male') }}</option>
                    <option value="female" {{ $patient['sex'] == 'female' ? 'selected' : '' }}>{{ __('female') }}
                    </option>
                </select>
            </div>

            <div class="my-5">
                <fieldset>
                    <legend class="h5">{{ __('Date of birth') }}</legend>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="birthDay">{{ __('Day') }}</label>
                                <input type="number" class="form-control" id="birthDay" name="birthDay" min="1" max="31"
                                    value={{ $patient['dob'] ? intval(date('d', strtotime($patient['dob']))) : null }}>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="birthMonth">{{ __('Month') }}</label>
                                <input type="number" class="form-control" id="birthMonth" name="birthMonth" min="1" max="12"
                                    value={{ $patient['dob'] ? intval(date('m', strtotime($patient['dob']))) : null }}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="birthYear">{{ __('Year') }}</label>
                                <input type="number" class="form-control" id="birthYear" name="birthYear" min="1900"
                                    max="2021" value={{ $patient['dob'] ? intval(date('Y', strtotime($patient['dob']))) : null }}>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>


            <div class="form-group">
                <label for="height">{{ __('Height') }} (cm)</label>
                <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$" class="form-control" id="height" name="height"
                    value={{ $patient['height'] }}>
            </div>

            <div class="form-group">
                <label for="weight">{{ __('Weight') }} (kg)</label>
                <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$" class="form-control" id="weight" name="weight"
                    value={{ $patient['weight'] }}>
            </div>

            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input type="email" class="form-control" id="email" name="email" value={{ $patient['email'] }}>
            </div>


            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <input type="password" class="form-control" id="password" name="password">
                <small>{{ __('Format:') }} {{ __('no conditions') }}</small>
            </div>

            <div class="form-group">
                <label for="mobile">{{ __('Mobile') }}</label>
                <input type="tel" class="form-control" id="mobile" name="mobile" value={{ $patient['mobile'] }}>
                <small>{{ __('Format:') }} +421000111222</small>
            </div>

            <div class="form-group">
                <label for="externalId">{{ __('External ID') }}</label>
                <input type="externalId" class="form-control" id="externalId" name="externalId"
                    value={{ $patient['id_external'] }}>
            </div>

            <div class="form-group">
                <label for="externalDoctorId">{{ __('External doctor ID') }}</label>
                <input type="externalDoctorId" class="form-control" id="externalDoctorId" name="externalDoctorId"
                    value={{ $patient['id_external_doctor'] }}>
            </div>

            <div class="form-group">
                <label for="mac">{{ __("The MAC address of patient's ECG sensor") }}</label>
                <input pattern="^([0-9A-Fa-f]{2}[:]){5}([0-9A-Fa-f]{2})$" type="text" class="form-control" id="mac"
                    name="mac" value={{ $patient['mac'] }}>
                <small>{{ __('Format:') }} 01:23:45:67:89:AB</small>
            </div>

            <div class="d-flex align-items-center my-5 justify-content-center">
                <a href="{{ route('coordinator.patients.profile', ['patient' => $patient->id]) }}"
                    class="btn btn-secondary mr-3">{{ __('Cancel') }}</a>
                <input type="hidden" name="patientId" id="patientId" value="{{ $patient->id }}">
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </div>

        </form>
    </div>
@endsection

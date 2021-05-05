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

        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3 class="mb-3">
                    {{ __('Personal information') }}
                </h3>
            </div>

            <div>
                <a href="{{ '/coordinator/patients/' . $patient['id'] . '/profile/edit' }}"
                    class="btn btn-outline-secondary">
                    <i class="fas fa-edit"></i>
                    {{ __('Edit information') }}
                </a>
            </div>
        </div>

        <table>
            <tr>
                <td class="font-weight-bold pr-3">
                    {{ __('Name') }}
                </td>
                <td>
                    {{ $patient['name'] ?? '--' }}
                </td>
            </tr>

            <tr>
                <td class="font-weight-bold pr-3">
                    {{ __('Surname') }}
                </td>
                <td>
                    {{ $patient['surname'] ?? '--' }}
                </td>
            </tr>

            <tr>
                <td class="font-weight-bold pr-3">
                    {{ __('Sex') }}
                </td>
                <td>
                    @if ($patient['sex'])
                        {{ __($patient['sex']) }}
                    @else
                        --
                    @endif
                </td>
            </tr>

            <tr>
                <td class="font-weight-bold pr-3">
                    {{ __('Age') }}
                </td>
                <td>
                    @if ($patient->age())
                        {{ $patient->age() . ' ' . __('years') }}
                    @else
                        --
                    @endif
                </td>
            </tr>

            <tr>
                <td class="font-weight-bold pr-3">
                    {{ __('Height') }}
                </td>
                <td>
                    @if ($patient['height'])
                        {{ $patient['height'] . ' cm' }}
                    @else
                        --
                    @endif
                </td>

            </tr>
            <tr>
                <td class="font-weight-bold pr-3">
                    {{ __('Weight') }}
                </td>
                <td>
                    @if ($patient['weight'])
                        {{ $patient['weight'] . ' kg' }}
                    @else
                        --
                    @endif
                </td>
            </tr>

            <tr>
                <td class="font-weight-bold pr-3">
                    {{ __('Email') }}
                </td>
                <td>
                    @if ($patient['email'])
                        <a href="mailto:{{ $patient['email'] }}">
                            {{ $patient['email'] }}
                        </a>
                    @else
                        --
                    @endif
                </td>
            </tr>

            <tr>
                <td class="font-weight-bold pr-3">
                    {{ __('Mobile') }}
                </td>
                <td>
                    @if ($patient['mobile'])
                        <a href="tel:{{ $patient['mobile'] }}">
                            {{ $patient['mobile'] }}
                        </a>
                    @else
                        --
                    @endif
                </td>
            </tr>

            <tr>
                <td class="font-weight-bold pr-3">
                    {{ __('External patient ID') }}
                </td>
                <td>
                    {{ $patient['id_external'] ?? '--' }}
                </td>
            </tr>

            <tr>
                <td class="font-weight-bold pr-3">
                    {{ __('External doctor ID') }}
                </td>
                <td>
                    {{ $patient['id_external_doctor'] ?? '--' }}

                </td>
            </tr>

            <tr>
                <td class="font-weight-bold pr-3">
                    {{ __("The MAC address of patient's ECG sensor") }}
                </td>
                <td>
                    {{ $patient['mac'] ?? '--' }}
                </td>
            </tr>
        </table>
    </div>
@endsection

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

        <div class="d-flex justify-content-between">
            <div>
                <h3 class="my-3">
                    {{ __('Contacts') }}
                </h3>
            </div>
            <div>
                <a href="{{ '/coordinator/patients/' . $patient['id'] . '/contacts/create' }}"
                    class="btn btn-outline-secondary">
                    <i class="fas fa-user-plus"></i>
                    {{ __('New contact') }}
                </a>
            </div>
        </div>

        <div class="mb-3">
            <div class="row">
                @if (count($contacts) == 0)
                <div class="col">
                    {{ __('The patient does not have any contacts.') }}
                </div>
                @else
                    @foreach ($contacts as $contact)
                        <div class="col">
                            <div class="card my-3">
                                <div class="card-body">
                                    <h4 class="card-title">
                                        {{ ucfirst(__($contact->type)) }}
                                    </h4>

                                    <div class="card-text">
                                        <table>
                                            <tr>
                                                <td class="font-weight-bold pr-3">
                                                    {{ __('Name') }}
                                                </td>
                                                <td>
                                                    {{ $contact->titles_prefix . ' ' . $contact->name . ' ' . $contact->surname . ' ' . $contact->titles_postfix }}
                                                </td>
                                            </tr>

                                            @if ($contact->email)
                                                <tr>
                                                    <td class="font-weight-bold pr-3">
                                                        {{ __('Email') }}
                                                    </td>
                                                    <td>
                                                        <a href="mailto:{{ $contact->email }}">
                                                            {{ $contact->email }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($contact->mobile)
                                                <tr>
                                                    <td class="font-weight-bold pr-3">
                                                        {{ __('Mobile') }}
                                                    </td>
                                                    <td>
                                                        <a href="tel:{{ $contact->mobile }}">
                                                            {{ $contact->mobile }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection

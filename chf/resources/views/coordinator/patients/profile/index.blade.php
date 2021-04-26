@extends('layouts.app')

@section('content')
<div class="container">
    <h1>
        {{ __('Patients') }}
    </h1>

    <h2>
        {{ $patient['name'].' '.$patient['surname'] }}
    </h2>

    <ul class="nav nav-tabs my-4">
        <li class="nav-item">
            <a class="{{ Request::is('*/profile*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/profile'}}">{{ __('Profile') }}</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/therapy*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/therapy'}}">{{ __('Therapy') }}</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/measurements*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/measurements'}}">{{ __('Measurements') }}</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/charts*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/charts'}}">{{ __('Charts') }}</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/contacts*') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/contacts'}}">{{ __('Contact') }}</a>
        </li>
    </ul>

    <h3 class="mb-3">
        {{ __('Personal information') }}
    </h3>


    <table>
        @if($patient['name'])
        <tr>
            <td class="font-weight-bold pr-3">
                {{ __('Name') }}
            </td>
            <td>
                {{ $patient['name'] }}
            </td>
        </tr>
        @endif

        @if($patient['surname'])
        <tr>
            <td class="font-weight-bold pr-3">
                {{ __('Surname') }}
            </td>
            <td>
                {{ $patient['surname'] }}
            </td>
        </tr>
        @endif

        @if($patient['sex'])
        <tr>
            <td class="font-weight-bold pr-3">
                {{ __('Sex') }}
            </td>
            <td>
                {{ __($patient['sex']) }}
            </td>
        </tr>
        @endif

        @if($patient->age())
        <tr>
            <td class="font-weight-bold pr-3">
                {{ __('Age') }}
            </td>
            <td>
                {{ $patient->age().' '.__('years') }}
            </td>
        </tr>
        @endif

        @if($patient['height'])
        <tr>
            <td class="font-weight-bold pr-3">
                {{ __('Height') }}
            </td>
            <td>
                {{ $patient['height'].' cm' }}
            </td>
        </tr>
        @endif

        @if($patient['weight'])
        <tr>
            <td class="font-weight-bold pr-3">
                {{ __('Weight') }}
            </td>
            <td>
                {{ $patient['weight'].' kg' }}
            </td>
        </tr>
        @endif

        @if($patient['email'])
        <tr>
            <td class="font-weight-bold pr-3">
                {{ __('Email') }}
            </td>
            <td>
                <a href="mailto:{{ $patient['email'] }}">
                    {{ $patient['email'] }}
                </a>
            </td>
        </tr>
        @endif

        @if($patient['mobile'])
        <tr>
            <td class="font-weight-bold pr-3">
                {{ __('Mobile') }}
            </td>
            <td>
                <a href="tel:{{ $patient['mobile'] }}">
                    {{ $patient['mobile'] }}
                </a>
            </td>
        </tr>
        @endif
    </table>
</div>
@endsection

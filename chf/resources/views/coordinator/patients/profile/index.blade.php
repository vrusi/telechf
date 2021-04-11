@extends('layouts.app')

@section('content')
<div class="container">
    <h1>
        Patients
    </h1>

    <h2>
        {{ $patient['name'].' '.$patient['surname'] }}
    </h2>

    <ul class="nav nav-tabs my-4">
        <li class="nav-item">
            <a class="{{ Request::is('*/profile') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/profile'}}">Profile</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/therapy') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/therapy'}}">Therapy</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/measurements') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/measurements'}}">Measurements</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/charts') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/charts'}}">Charts</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/contacts') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/contacts'}}">Contact</a>
        </li>
    </ul>

    <h3 class="mb-3">
        Personal information
    </h3>


    <table>
        @if($patient['name'])
        <tr>
            <td class="font-weight-bold pr-3">
                Name
            </td>
            <td>
                {{ $patient['name'] }}
            </td>
        </tr>
        @endif

        @if($patient['surname'])
        <tr>
            <td class="font-weight-bold pr-3">
                Surname
            </td>
            <td>
                {{ $patient['surname'] }}
            </td>
        </tr>
        @endif

        @if($patient['email'])
        <tr>
            <td class="font-weight-bold pr-3">
                Email
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
                Mobile
            </td>
            <td>
                <a href="tel:{{ $patient['mobile'] }}">
                    {{ $patient['mobile'] }}
                </a>
            </td>
        </tr>
        @endif

        @if($patient['sex'])
        <tr>
            <td class="font-weight-bold pr-3">
                Sex
            </td>
            <td>
                {{ $patient['sex'] }}
            </td>
        </tr>
        @endif

        @if($patient['age'])
        <tr>
            <td class="font-weight-bold pr-3">
                Age
            </td>
            <td>
                {{ $patient['age'].' years' }}
            </td>
        </tr>
        @endif

        @if($patient['height'])
        <tr>
            <td class="font-weight-bold pr-3">
                Height
            </td>
            <td>
                {{ $patient['height'].' cm' }}
            </td>
        </tr>
        @endif

        @if($patient['weight'])
        <tr>
            <td class="font-weight-bold pr-3">
                Weight
            </td>
            <td>
                {{ $patient['weight'].' kg' }}
            </td>
        </tr>
        @endif
    </table>
</div>
@endsection

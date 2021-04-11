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


    <h3 class="my-3">
        Chat
    </h3>

    <div>
        TODO
    </div>

    <h3 class="my-3">
        Contacts
    </h3>

    <div class="mb-3">
        @foreach($contacts as $contact)
        <address class="my-3">
            <h4>
                {{ $patient['name'].'\'s'}} {{$contact->type}}
            </h4>

            <table>
                <tbody>
                    <tr>
                        <td class="font-weight-bold pr-3">
                            Name
                        </td>
                        <td>
                            {{$contact->titles_prefix.' '.$contact->name.' '.$contact->surname.' '.$contact->titles_postfix}}
                        </td>
                    </tr>

                    @if($contact->email)
                    <tr>
                        <td class="font-weight-bold pr-3">
                            E-mail
                        </td>
                        <td>
                            <a href="mailto:{{$contact->email}}">
                                {{$contact->email}}
                            </a>
                        </td>
                    </tr>
                    @endif
                    @if($contact->mobile)
                    <tr>
                        <td class="font-weight-bold pr-3">
                            Mobile
                        </td>
                        <td>
                            <a href="tel:{{$contact->mobile}}">
                                {{$contact->mobile}}
                            </a>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </address>

        @endforeach
    </div>

</div>


@endsection

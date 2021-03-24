@extends('layouts.app')

@section('content')


    <div class="container">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="m-3">
                        @foreach($contacts as $contact)
                        <address class="my-3">
                            <h3>
                                Your {{$contact->type}}
                            </h3>

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
                                            {{$contact->email}}
                                        </td>
                                    </tr>
                                    @endif
                                    @if($contact->mobile)
                                    <tr>
                                        <td class="font-weight-bold pr-3">
                                            Mobile
                                        </td>
                                        <td>
                                            {{$contact->mobile}}
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </address>

                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection

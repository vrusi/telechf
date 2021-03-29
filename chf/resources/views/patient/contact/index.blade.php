@extends('layouts.app')

@section('content')
<div class="container patient">
    <h1 class="mb-3">Your contacts</h1>

    <div class="mb-3">
        @foreach($contacts as $contact)
        <address class="my-3">
            <h2>
                Your {{$contact->type}}
            </h2>

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

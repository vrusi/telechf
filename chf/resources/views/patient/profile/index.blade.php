@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div class="container patient">
    <h1 class="mb-3">
        Personal information
    </h1>

    <div class="row">
        <div class="col-8">


            <table>
                @if($user->name)
                <tr>
                    <td class="font-weight-bold pr-3">
                        First name
                    </td>
                    <td>
                        {{ $user->name }}
                    </td>
                </tr>
                @endif

                @if($user->surname)
                <tr>
                    <td class="font-weight-bold pr-3">
                        Last name
                    </td>
                    <td>
                        {{ $user->surname }}
                    </td>
                </tr>
                @endif

                @if($user->email)
                <tr>
                    <td class="font-weight-bold pr-3">
                        Email
                    </td>
                    <td>
                        <a href="mailto:{{ $user->email }}">
                            {{ $user->email }}
                        </a>
                    </td>
                </tr>
                @endif

                @if($user->mobile)
                <tr>
                    <td class="font-weight-bold pr-3">
                        Mobile
                    </td>
                    <td>
                        <a href="tel:{{ $user->mobile }}">
                            {{ $user->mobile }}
                        </a>
                    </td>
                </tr>
                @endif

                @if($user->sex)
                <tr>
                    <td class="font-weight-bold pr-3">
                        Sex
                    </td>
                    <td>
                        {{ $user->sex }}
                    </td>
                </tr>
                @endif

                @if($user->age())
                <tr>
                    <td class="font-weight-bold pr-3">
                        Age
                    </td>
                    <td>
                        {{ $user->age().' years' }}
                    </td>
                </tr>
                @endif

                @if($user->height)
                <tr>
                    <td class="font-weight-bold pr-3">
                        Height
                    </td>
                    <td>
                        {{ $user->height.' cm' }}
                    </td>
                </tr>
                @endif

                @if($user->weight)
                <tr>
                    <td class="font-weight-bold pr-3">
                        Weight
                    </td>
                    <td>
                        {{ $user->weight.' kg' }}
                    </td>
                </tr>
                @endif
            </table>
        </div>
        <div class="col-4">
            <a class="btn btn-outline-secondary" role="button" href="{{ url('/profile/create') }}">
                <div class="d-flex align-items-center">
                    <i class="fas fa-edit pr-1"></i>
                    <div>Edit</div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
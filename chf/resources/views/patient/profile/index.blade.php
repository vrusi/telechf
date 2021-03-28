@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div class="container">

    <h1>
        Personal information
    </h1>

    <table>
        @if($name)
        <tr>
            <td class="font-weight-bold pr-3">
                Name
            </td>
            <td>
                {{ $name }}
            </td>
        </tr>
        @endif

        @if($surname)
        <tr>
            <td class="font-weight-bold pr-3">
                Surname
            </td>
            <td>
                {{ $surname }}
            </td>
        </tr>
        @endif

        @if($email)
        <tr>
            <td class="font-weight-bold pr-3">
                Email
            </td>
            <td>
                <a href="mailto:{{ $email }}">
                    {{ $email }}
                </a>
            </td>
        </tr>
        @endif

        @if($mobile)
        <tr>
            <td class="font-weight-bold pr-3">
                Mobile
            </td>
            <td>
                <a href="tel:{{ $mobile }}">
                    {{ $mobile }}
                </a>
            </td>
        </tr>
        @endif

        @if($sex)
        <tr>
            <td class="font-weight-bold pr-3">
                Sex
            </td>
            <td>
                {{ $sex }}
            </td>
        </tr>
        @endif

        @if($age)
        <tr>
            <td class="font-weight-bold pr-3">
                Age
            </td>
            <td>
                {{ $age.' years' }}
            </td>
        </tr>
        @endif

        @if($height)
        <tr>
            <td class="font-weight-bold pr-3">
                Height
            </td>
            <td>
                {{ $height.' cm' }}
            </td>
        </tr>
        @endif

        @if($weight)
        <tr>
            <td class="font-weight-bold pr-3">
                Weight
            </td>
            <td>
                {{ $weight.' kg' }}
            </td>
        </tr>
        @endif
    </table>
</div>
@endsection

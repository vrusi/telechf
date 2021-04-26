@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div class="container patient">
    <h1 class="mb-3">
        {{ __('Personal information') }}
    </h1>

    <div class="row">
        <div class="col-8">
            <table>
                @if($user->name)
                <tr>
                    <td class="font-weight-bold pr-3">
                        {{ __('First name') }}
                    </td>
                    <td>
                        {{ $user->name }}
                    </td>
                </tr>
                @endif

                @if($user->surname)
                <tr>
                    <td class="font-weight-bold pr-3">
                        {{ __('Last name') }}
                    </td>
                    <td>
                        {{ $user->surname }}
                    </td>
                </tr>
                @endif

                @if($user->email)
                <tr>
                    <td class="font-weight-bold pr-3">
                        {{ __('E-Mail Address') }}
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
                        {{ __('Mobile') }}
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
                        {{ __('Sex') }}
                    </td>
                    <td>
                        {{ __($user->sex) }}
                    </td>
                </tr>
                @endif

                @if($user->age())
                <tr>
                    <td class="font-weight-bold pr-3">
                        {{ __('Age') }}
                    </td>
                    <td>
                        {{ $user->age().' '.__('years') }}
                    </td>
                </tr>
                @endif

                @if($user->height)
                <tr>
                    <td class="font-weight-bold pr-3">
                        {{ __('Height') }}
                    </td>
                    <td>
                        {{ $user->height.' cm' }}
                    </td>
                </tr>
                @endif

                @if($user->weight)
                <tr>
                    <td class="font-weight-bold pr-3">
                        {{ __('Weight') }}
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
                    <div>{{ __('Edit') }}</div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
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
                    <tr>
                        <td class="font-weight-bold pr-3">
                            {{ __('First name') }}
                        </td>
                        <td>
                            @if ($user->name)
                                {{ $user->name }}
                            @else
                                --
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td class="font-weight-bold pr-3">
                            {{ __('Last name') }}
                        </td>
                        <td>
                            @if ($user->surname)
                                {{ $user->surname }}
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
                            @if ($user->email)
                                <a href="mailto:{{ $user->email }}">
                                    {{ $user->email }}
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
                            @if ($user->mobile)
                                <a href="tel:{{ $user->mobile }}">
                                    {{ $user->mobile }}

                                </a>
                            @else
                                --
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td class="font-weight-bold pr-3">
                            {{ __('Sex') }}
                        </td>
                        <td>
                            @if ($user->sex)
                                {{ __($user->sex) }}
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
                            @if ($user->age())
                                {{ $user->age() . ' ' . __('years') }}
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
                            @if ($user->height)
                                {{ $user->height . ' cm' }}
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
                            @if ($user->weight)
                                {{ $user->weight . ' kg' }}
                            @else
                                --
                            @endif
                        </td>
                    </tr>
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

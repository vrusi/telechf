@extends('layouts.app')

@section('title', 'Profile')

@section('content')

    <div class="container patient">
        <h1 class="mb-3">
            {{ __('Personal information') }}
        </h1>

        <form method="POST" action="/profile">
            <div class="row">
                <div class="col-8">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="text-red-500">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @csrf
                    <div>
                        @if ($user->email)
                            <div class="form-group">
                                <label for="email"> {{ __('Email') }} </label>
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="{{ $user->email }}">
                            </div>
                        @endif

                        @if ($user->mobile)
                            <div class="form-group">
                                <label for="mobile"> {{ __('Mobile') }} </label>
                                <input type="tel" class="form-control" name="mobile" id="mobile"
                                    placeholder="{{ $user->mobile }}">
                            </div>
                        @endif
                    </div>
                    <table>
                        @if ($user->name)
                            <tr>
                                <td class="font-weight-bold pr-3">
                                    {{ __('First name') }}
                                </td>
                                <td>
                                    {{ $user->name }}
                                </td>
                            </tr>
                        @endif

                        @if ($user->surname)
                            <tr>
                                <td class="font-weight-bold pr-3">
                                    {{ __('Last name') }}
                                </td>
                                <td>
                                    {{ $user->surname }}
                                </td>
                            </tr>
                        @endif

                        @if ($user->sex)
                            <tr>
                                <td class="font-weight-bold pr-3">
                                    {{ __('Sex') }}
                                </td>
                                <td>
                                    {{ __($user->sex) }}
                                </td>
                            </tr>
                        @endif

                        @if ($user->age())
                            <tr>
                                <td class="font-weight-bold pr-3">
                                    {{ __('Age') }}
                                </td>
                                <td>
                                    {{ $user->age() . ' ' . __('years') }}
                                </td>
                            </tr>
                        @endif

                        @if ($user->height)
                            <tr>
                                <td class="font-weight-bold pr-3">
                                    {{ __('Height') }}
                                </td>
                                <td>
                                    {{ $user->height . ' cm' }}
                                </td>
                            </tr>
                        @endif

                        @if ($user->weight)
                            <tr>
                                <td class="font-weight-bold pr-3">
                                    {{ __('Weight') }}
                                </td>
                                <td>
                                    {{ $user->weight . ' kg' }}
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>


            <div class="d-flex justify-content-center align-items-center py-3">
                <a class="btn btn-outline-secondary mr-3" href="{{ route('profile') }}">{{ __('Cancel') }}</a>
                <button class="btn btn-primary" type="submit">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-save pr-1"></i>
                        <div>{{ __('Save') }}</div>
                    </div>
                </button>
            </div>

        </form>

    </div>
@endsection

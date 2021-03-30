@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div class="container patient">
    <h1 class="mb-3">
        Personal information
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
                    @if($email)
                    <div class="form-group">
                        <label for="email"> Email </label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="{{$email}}">
                    </div>
                    @endif

                    @if($mobile)
                    <div class="form-group">
                        <label for="mobile"> Mobile </label>
                        <input type="tel" class="form-control" name="mobile" id="mobile" placeholder="{{$mobile}}">
                    </div>
                    @endif
                </div>
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
            <div class="col-4">
                <button class="btn btn-outline-secondary" type="submit">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-save pr-1"></i>
                        <div>Save</div>
                    </div>
                </button>
            </div>
        </div>
    </form>

</div>
@endsection

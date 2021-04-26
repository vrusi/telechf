@extends('layouts.app')

@section('content')

<style>
    table {
        width: 100%;
    }

    th,
    td {
        padding: 0.5rem;
    }

</style>
<div class="container">

    <div class="d-flex justify-content-between align-content-center">
        <div>
            <h1>{{ __('Patients') }}</h1>
        </div>

        <div>
            <a href="{{ route('patients.create') }}" class="btn btn-outline-secondary">
                <i class="fas fa-user-plus"></i>
                {{ __('New patient') }}
            </a>
        </div>

    </div>

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="{{ $active ? 'nav-link active' : 'nav-link' }}" href="{{ route('patients.index', ['inactive' => false] ) }}">{{ __('Active patients') }}</a>
        </li>

        <li class="nav-item">
            <a class="{{ !$active ? 'nav-link active' : 'nav-link' }}" href="{{ route('patients.index', ['inactive' => true]) }}">{{ __('Inactive patients') }}</a>
        </li>
    </ul>

    <p class="my-3">
        @if($active)
        {{ __('These are all the patients with active accounts that were assigned to you.') }}
        @else
        {{ __('These are all the patients with deactivated accounts that were assigned to you.') }}
        @endif
    </p>

    <table id="patients-table">
        <thead>
            <tr>
                <th>
                    {{ __('Name') }}
                </th>
                <th>
                    {{ __('Surname') }}

                </th>
                <th>
                    {{ __('Sex') }}
                </th>
                <th>
                    {{ __('Age') }}
                </th>
                <th>
                    {{ __('Height') }}
                </th>
                <th>
                    {{ __('Weight') }}
                </th>
                <th>
                    {{ __('Email') }}
                </th>
                <th>
                    {{ __('Mobile') }}
                </th>
                @if($active)
                <th>
                    {{ __('Detail') }}
                </th>
                @endif
                <th>
                    @if($active)
                    {{ __('Deactivate') }}
                    @else
                    {{ __('Restore') }}
                    @endif
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $patient)
            <tr>
                <td>
                    {{ $patient['name'] ?? '--' }}
                </td>
                <td>
                    {{ $patient['surname'] ?? '--' }}
                </td>

                <td>
                    {{ __($patient['sex']) ?? '--' }}
                </td>

                <td> 

                    {{ $patient->age().' '.__('years') ?? '--' }}
                </td>

                <td>
                    {{ $patient['height'].' cm' ?? '--' }}
                </td>

                <td>
                    {{ $patient['weight'].' kg' ?? '--' }}
                </td>
                <td>
                    <a href="mailto:{{ $patient['email'] }}">
                        {{ $patient['email'] }}
                    </a>
                </td>
                <td>
                    <a href="tel:{{ $patient['mobile'] }}">
                        {{ $patient['mobile'] }}
                    </a>
                </td>
                @if($active)
                <td>
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="{{'/coordinator/patients/'.$patient['id'] }}">
                            <i class="fas fa-chevron-circle-right"></i>
                        </a>
                    </div>
                </td>
                @endif
                <td>
                    @if($active)
                    <a class="btn btn-outline-danger btn-sm" href="{{ url('/coordinator/patients/'.$patient['id'].'/deactivate') }}">
                        <i class="fas fa-user-minus"></i>
                    </a>
                    @else
                    <a class="btn btn-outline-success btn-sm" href="{{ url('/coordinator/patients/'.$patient['id'].'/restore') }}">
                        <i class="fas fa-user-plus"></i>
                    </a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $.noConflict();
        $('#patients-table').DataTable({
            responsive: true,
            "language": {
                "url": navigator.language === 'sk' ? '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Slovak.json' : '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json',
            },
        });
    });

</script>
@endsection

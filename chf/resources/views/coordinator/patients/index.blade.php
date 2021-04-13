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
            <h1>Patients</h1>
        </div>

        <div>
            <a href="{{ route('patients.create') }}" class="btn btn-outline-secondary">
                <i class="fas fa-user-plus"></i>
                Add new patient
            </a>
        </div>

    </div>

    <p>
        These are all the patients that were assigned to you.
    </p>

    <table id="patients-table">
        <thead>
            <tr>
                <th>
                    Name
                </th>
                <th>
                    Surname
                </th>
                <th>
                    Sex
                </th>
                <th>
                    Age
                </th>
                <th>
                    Height
                </th>
                <th>
                    Weight
                </th>
                <th>
                    Email
                </th>
                <th>
                    Mobile
                </th>
                <th>
                    Detail
                </th>
                <th>
                    Delete
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
                    {{ $patient['sex'] ?? '--' }}
                </td>

                <td>
                    {{ $patient['age'].' years' ?? '--' }}
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
                <td>
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="{{'/coordinator/patients/'.$patient['id'] }}">
                            <i class="fas fa-chevron-circle-right"></i>
                        </a>
                    </div>
                </td>
                <td>

                    <a class="btn btn-outline-danger btn-sm" href="{{ '/coordinator/patients/'.$patient['id'].'/destroy' }}">
                        <i class="fas fa-user-minus"></i>
                    </a>
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
            responsive: true
        , });
    });

</script>
@endsection

@extends('layouts.app')

@section('content')

<style>
    table {
        width: 100%;
    }

    th, td {
        padding: 0.5rem;
    }

</style>
<div class="container">
    <h1>Patients</h1>

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

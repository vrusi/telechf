@extends('layouts.app')

@section('content')
<style>
    .alarm-safety {
        background: #ff000020;
        color: firebrick;
        font-weight: 900;
    }

    .faint {
        color: #00000080;

    }

    .alarm-safety .faint {
        color: #b2222280;
    }

    .alarm-therapeutic {
        background: #FEF3E5;
        color: rgba(245, 154, 35, 0.87);
        font-weight: 900;
    }

    .alarm-therapeutic .faint {
        color: rgba(245, 154, 35, 0.50);
    }

    th,
    td {
        min-width: 70px;
        padding: 1rem;
    }

    table {
        width: 100%;
    }

</style>


<div class="container">
    <h1>
        Patients
    </h1>

    <h2>
        {{ $patient['name'].' '.$patient['surname'] }}
    </h2>

    <h3>
        Measurements
    </h3>
</div>

<script>


</script>
@endsection

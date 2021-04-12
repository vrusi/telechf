@extends('layouts.app')

@section('content')
<style>
    th,
    td {
        padding: 1rem 0;
        border-width: 0 0 1px 0;
        border-style: solid;
        border-color: #00000020;
    }

    .container {
        max-width: 90vw;
    }


    input {
        max-width: 100px;
    }


    table {
        width: 100%;
    }

</style>

<div class="container">
    <h1>
        Patients
    </h1>

    <h3>
        {{ $patient['name'].' '.$patient['surname'] }}
    </h3>

    <ul class="nav nav-tabs my-4">
        <li class="nav-item">
            <a class="{{ Request::is('*/profile') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/profile'}}">Profile</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/therapy') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/therapy'}}">Therapy</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/measurements') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/measurements'}}">Measurements</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/charts') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/charts'}}">Charts</a>
        </li>
        <li class="nav-item">
            <a class="{{ Request::is('*/contacts') ? 'nav-link active' : 'nav-link' }}" href="{{'/coordinator/patients/'.$patient['id'].'/contacts'}}">Contact</a>
        </li>
    </ul>

    <div class="my-3">
        <h3>
            Monitored parameters
        </h3>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="store">
            @csrf
            <table class="mb-5">
                <thead>
                    <tr>
                        <th class="pr-3 col-first">
                            Parameter
                        </th>

                        <th class="pr-3">
                            Personal safety threshold
                        </th>


                        <th class="pr-3">
                            Personal therapeutic threshold
                        </th>

                        <th>
                            Measurement frequency
                        </th>
                    </tr>

                </thead>
                <tbody>

                    @foreach($parameters as $parameter)
                    <tr>
                        <td class="pr-3 col-first">
                            {{ $parameter['name'] }}
                        </td>

                        <td class="pr-3">
                            @if(strtolower($parameter['name']) != 'ecg')
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="{{ 'parameter'.$parameter->id.'minSafety' }}">Minimum</label>
                                        <div class="d-flex align-items-center">
                                            <input type="number" class="form-control" id="{{ 'parameter'.$parameter->id.'minSafety' }}" name="{{ 'parameter'.$parameter->id.'minSafety' }}" placeholder="{{ $parameter->pivot->threshold_safety_min ?? '--' }}">
                                            <label class="px-3"> {{ $parameter->unit }} </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="{{ 'parameter'.$parameter->id.'maxSafety' }}">Maximum</label>
                                        <div class="d-flex align-items-center">
                                            <input type="number" class="form-control" id="{{ 'parameter'.$parameter->id.'maxSafety' }}" name="{{ 'parameter'.$parameter->id.'maxSafety' }}" placeholder="{{ $parameter->pivot->threshold_safety_max ?? '--' }}">
                                            <label class="px-3"> {{ $parameter->unit }} </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    @if($parameter->pivot->threshold_safety_min)
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" id="{{ 'parameter'.$parameter->id.'minSafetyCheck' }}" name="{{ 'parameter'.$parameter->id.'minSafetyCheck' }}">
                                        <label class="form-check-label" for="{{ 'parameter'.$parameter->id.'minSafetyCheck' }}"><i class="far fa-trash-alt"></i> Remove</label>
                                    </div>
                                    @endif
                                </div>

                                <div class="col-6">
                                    @if($parameter->pivot->threshold_safety_max)
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" id="{{ 'parameter'.$parameter->id.'maxSafetyCheck' }}" name="{{ 'parameter'.$parameter->id.'maxSafetyCheck' }}">
                                        <label class="form-check-label" for="{{ 'parameter'.$parameter->id.'maxSafetyCheck' }}"><i class="far fa-trash-alt"></i> Remove</label>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </td>

                        <td>
                            @if(strtolower($parameter['name']) != 'ecg')
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="{{ 'parameter'.$parameter->id.'minTherapeutic' }}">Minimum</label>
                                        <div class="d-flex align-items-center">
                                            <input type="number" class="form-control" id="{{ 'parameter'.$parameter->id.'minTherapeutic' }}" name="{{ 'parameter'.$parameter->id.'minTherapeutic' }}" placeholder="{{ $parameter->pivot->threshold_therapeutic_min ?? '--' }}">
                                            <label class="px-3"> {{ $parameter->unit }} </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="{{ 'parameter'.$parameter->id.'maxTherapeutic' }}">Maximum</label>
                                        <div class="d-flex align-items-center">
                                            <input type="number" class="form-control" id="{{ 'parameter'.$parameter->id.'maxTherapeutic' }}" name="{{ 'parameter'.$parameter->id.'maxTherapeutic' }}" placeholder="{{ $parameter->pivot->threshold_therapeutic_max ?? '--' }}">
                                            <label class="px-3"> {{ $parameter->unit }} </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    @if($parameter->pivot->threshold_therapeutic_min)
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" id="{{ 'parameter'.$parameter->id.'minTherapeuticCheck' }}" name="{{ 'parameter'.$parameter->id.'minTherapeuticCheck' }}">
                                        <label class="form-check-label" for="{{ 'parameter'.$parameter->id.'minTherapeuticCheck' }}"><i class="far fa-trash-alt"></i> Remove</label>
                                    </div>
                                    @endif
                                </div>

                                <div class="col-6">
                                    @if($parameter->pivot->threshold_therapeutic_max)
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" id="{{ 'parameter'.$parameter->id.'maxTherapeuticCheck' }}" name="{{ 'parameter'.$parameter->id.'maxTherapeuticCheck' }}">
                                        <label class="form-check-label" for="{{ 'parameter'.$parameter->id.'maxTherapeuticCheck' }}"><i class="far fa-trash-alt"></i> Remove</label>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                        </td>

                        <td>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="{{ 'parameter'.$parameter->id.'times' }}">Times</label>
                                        <input type="number" class="form-control" id="{{ 'parameter'.$parameter->id.'times' }}" name="{{ 'parameter'.$parameter->id.'times' }}" placeholder="{{ $parameter->pivot->measurement_times ?? '--' }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="{{ 'parameter'.$parameter->id.'per' }}">Per</label>
                                        <select class="form-control" id="{{ 'parameter'.$parameter->id.'per' }}" name="{{ 'parameter'.$parameter->id.'per' }}">
                                            <option value="" {{ $parameter->pivot->measurement_span == null ? 'selected' : '' }}>--</option>
                                            <option value="hour" {{ $parameter->pivot->measurement_span == 'hour' ? 'selected' : '' }}>hour</option>
                                            <option value="day" {{ $parameter->pivot->measurement_span == 'day' ? 'selected' : '' }}>day</option>
                                            <option value="week" {{ $parameter->pivot->measurement_span == 'week' ? 'selected' : '' }}>week</option>
                                            <option value="month" {{ $parameter->pivot->measurement_span == 'month' ? 'selected' : '' }}>month</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    @if($parameter->pivot->measurement_times && $parameter->pivot->measurement_span)
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" id="{{ 'parameter'.$parameter->id.'freqCheck' }}" name="{{ 'parameter'.$parameter->id.'freqCheck' }}">
                                        <label class="form-check-label" for="{{ 'parameter'.$parameter->id.'freqCheck' }}"><i class="far fa-trash-alt"></i> Remove measurement frequency</label>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <input type="hidden" name="patientId" value="{{$patient['id']}}">

            <div class="d-flex mb-5 justify-content-center">
                <div class="mr-3">
                    <a href="{{ route('coordinator.patients.therapy', ['patient' => $patient['id'] ]) }}" class="btn btn-secondary">Cancel</a>
                </div>

                <div class="ml-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>
                        Save personal thresholds
                    </button>
                </div>

            </div>
        </form>
    </div>
    @endsection

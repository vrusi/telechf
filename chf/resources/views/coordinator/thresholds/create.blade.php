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

</style>

<div class="container">
    <h1>
        Global thresholds
    </h1>
    <p class="text-justify">
        These threshold settings apply to every patient by default unless personal thresholds have been set for a patient. You can set personal thresholds for specific patients by visiting the thresholds tab within their profile.
    </p>

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
                    <th class="pr-3">
                        Parameter
                    </th>

                    <th class="pr-3">
                        Safety threshold
                    </th>

                    <th>
                        Measurement frequency
                    </th>
                </tr>

            </thead>
            <tbody>

                @foreach($parameters as $parameter)
                <tr>
                    <td class="pr-3">
                        {{ $parameter['name'] }}
                    </td>

                    <td class="pr-3">
                        @if(strtolower($parameter['name']) != 'ecg')
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="{{ 'parameter'.$parameter->id.'min' }}">Minimum</label>
                                    <div class="d-flex align-items-center">
                                        <input type="number" class="form-control" id="{{ 'parameter'.$parameter->id.'min' }}" name="{{ 'parameter'.$parameter->id.'min' }}" placeholder="{{ $parameter->threshold_min ?? '--' }}">
                                        <label class="px-3"> {{ $parameter->unit }} </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="{{ 'parameter'.$parameter->id.'max' }}">Maximum</label>
                                    <div class="d-flex align-items-center">
                                        <input type="number" class="form-control" id="{{ 'parameter'.$parameter->id.'max' }}" name="{{ 'parameter'.$parameter->id.'max' }}" placeholder="{{ $parameter->threshold_max ?? '--' }}">
                                        <label class="px-3"> {{ $parameter->unit }} </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                @if($parameter->threshold_min)
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="{{ 'parameter'.$parameter->id.'minCheck' }}" name="{{ 'parameter'.$parameter->id.'minCheck' }}">
                                    <label class="form-check-label" for="{{ 'parameter'.$parameter->id.'minCheck' }}"><i class="far fa-trash-alt"></i> Remove minimum threshold</label>
                                </div>
                                @endif
                            </div>

                            <div class="col-6">
                                @if($parameter->threshold_max)
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="{{ 'parameter'.$parameter->id.'maxCheck' }}" name="{{ 'parameter'.$parameter->id.'maxCheck' }}">
                                    <label class="form-check-label" for="{{ 'parameter'.$parameter->id.'maxCheck' }}"><i class="far fa-trash-alt"></i> Remove maximum threshold</label>
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
                                    <input type="number" class="form-control" id="{{ 'parameter'.$parameter->id.'times' }}" name="{{ 'parameter'.$parameter->id.'times' }}" placeholder="{{ $parameter->measurement_times ?? '--' }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="{{ 'parameter'.$parameter->id.'per' }}">Per</label>
                                    <select class="form-control" id="{{ 'parameter'.$parameter->id.'per' }}" name="{{ 'parameter'.$parameter->id.'per' }}">
                                        <option value="" {{ $parameter->measurement_span == null ? 'selected' : '' }}>--</option>
                                        <option value="hour" {{ $parameter->measurement_span == 'hour' ? 'selected' : '' }}>hour</option>
                                        <option value="day" {{ $parameter->measurement_span == 'day' ? 'selected' : '' }}>day</option>
                                        <option value="week" {{ $parameter->measurement_span == 'week' ? 'selected' : '' }}>week</option>
                                        <option value="month" {{ $parameter->measurement_span == 'month' ? 'selected' : '' }}>month</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                @if($parameter->measurement_times && $parameter->measurement_span)
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

        <div class="d-flex mb-5 justify-content-center">
            <div class="mr-3">
                <a href="{{ route('coordinator.thresholds') }}" class="btn btn-secondary">Cancel</a>
            </div>

            <div class="ml-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i>
                    Save global thresholds
                </button>
            </div>

        </div>
    </form>
</div>
@endsection

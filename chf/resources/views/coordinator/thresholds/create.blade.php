@extends('layouts.app')

@section('content')

<div class="container">
    <h1>
        Global thresholds
    </h1>
    <p>
        These threshold settings apply to every patient by default unless personal thresholds have been set for a patient. You can set personal thresholds for specific patients by visiting the thresholds tab within their profile.
    </p>

    <form method="POST" action="store">
        @csrf
        <table>
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
                                        <option value="null" {{ $parameter->measurement_span == null ? 'selected' : '' }}>--</option>
                                        <option value="hour" {{ $parameter->measurement_span == 'hour' ? 'selected' : '' }}>hour</option>
                                        <option value="day" {{ $parameter->measurement_span == 'day' ? 'selected' : '' }}>day</option>
                                        <option value="week" {{ $parameter->measurement_span == 'week' ? 'selected' : '' }}>week</option>
                                        <option value="month" {{ $parameter->measurement_span == 'month' ? 'selected' : '' }}>month</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">
            Save global thresholds
        </button>
    </form>
</div>
@endsection

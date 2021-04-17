@extends('layouts.app')

@section('content')

<div class="container">

    <h1>New patient form</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('patients.store') }}">
        @csrf
        <div class="row">
            <div class="col-6">
                <h2>Personal information</h2>
                <div class="row">
                    <div class="col-6">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">First Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="John" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="surname">Surname</label>
                                <input type="text" class="form-control" id="surname" name="surname" placeholder="Doe" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="sex">Sex</label>
                                <select id="sex" name="sex" class="form-control" required>
                                    <option value="male" selected>Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <fieldset>
                                <legend class="h5">Date of birth</legend>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="birthDay">Day</label>
                                            <input type="number" class="form-control" id="birthDay" name="birthDay" placeholder="1" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="birthMonth">Month</label>
                                            <input type="number" class="form-control" id="birthMonth" name="birthMonth" placeholder="1" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="birthYear">Year</label>
                                            <input type="number" class="form-control" id="birthYear" name="birthYear" placeholder="1970" required>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="height">Height (cm)</label>
                                <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$" class="form-control" id="height" name="height" placeholder="180" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="weight">Weight (kg)</label>
                                <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$" class="form-control" id="weight" name="weight" placeholder="90" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="email">Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                                </div>
                                <input type="email" class="form-control" id="email" name="email" placeholder="your@email.com" required>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="form-group">
                                <label for="mobile">Mobile</label>
                                <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="+447700900111">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="secret" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <h2>Therapy information</h2>

                <div class="parameters">
                    <h3>Parameters to monitor</h3>
                    @foreach($parameters as $parameter)
                    @if($parameter->fillable)
                    <button class="btn btn-outline-primary mr-1 mt-2" type="button" data-toggle="collapse" data-target="#{{ 'collapse'.$parameter->id.'param' }}" aria-expanded="false" aria-controls="{{ 'collapse'.$parameter->id.'param' }}">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="{{ 'parameter'.$parameter->id.'check' }}" name="{{ 'parameter'.$parameter->id.'check' }}">
                            <label class="form-check-label" for="{{ 'parameter'.$parameter->id.'check' }}">{{ $parameter->name }}</label>
                        </div>
                    </button>

                    <div class="collapse" id="{{ 'collapse'.$parameter->id.'param' }}">
                        <div class="card card-body">
                            <h4>
                                {{ $parameter->name }}
                            </h4>

                            @if(strtolower($parameter->name) != 'ecg')
                            <h5>Safety thresholds</h5>
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-3">
                                    <label for="{{ 'parameter'.$parameter->id.'MinSafety' }}">Minimum ({{ $parameter->unit }})</label>
                                    <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$" class="form-control" id="{{ 'parameter'.$parameter->id.'MinSafety' }}" name="{{ 'parameter'.$parameter->id.'MinSafety' }}" placeholder="Enter value">
                                </div>

                                <div class="form-group">
                                    <label for="{{ 'parameter'.$parameter->id.'MaxSafety' }}">Maximum ({{ $parameter->unit }})</label>
                                    <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$" class="form-control" id="{{ 'parameter'.$parameter->id.'MaxSafety' }}" name="{{ 'parameter'.$parameter->id.'MaxSafety' }}" placeholder="Enter value">
                                </div>
                            </div>

                            <h5>Therapeutic thresholds</h5>
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-3">
                                    <label for="{{ 'parameter'.$parameter->id.'MinTherapeutic' }}">Minimum ({{ $parameter->unit }})</label>
                                    <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$" class="form-control" id="{{ 'parameter'.$parameter->id.'MinTherapeutic' }}" name="{{ 'parameter'.$parameter->id.'MinTherapeutic' }}" placeholder="Enter value">
                                </div>

                                <div class="form-group">
                                    <label for="{{ 'parameter'.$parameter->id.'MaxTherapeutic' }}">Maximum ({{ $parameter->unit }})</label>
                                    <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$" class="form-control" id="{{ 'parameter'.$parameter->id.'MaxTherapeutic' }}" name="{{ 'parameter'.$parameter->id.'MaxTherapeutic' }}" placeholder="Enter value">
                                </div>
                            </div>
                            @endif

                            <h5>Measurement frequency</h5>
                            <div class="d-flex align-items-center">

                                <div class="form-group mr-3">
                                    <label for="{{ 'parameter'.$parameter->id.'times' }}">Times</label>
                                    <input type="number" class="form-control" id="{{ 'parameter'.$parameter->id.'times' }}" name="{{ 'parameter'.$parameter->id.'times' }}" placeholder="3">
                                </div>

                                <div class="form-group">
                                    <label for="{{ 'parameter'.$parameter->id.'per' }}">Per</label>
                                    <select class="form-control" id="{{ 'parameter'.$parameter->id.'per' }}" name="{{ 'parameter'.$parameter->id.'per' }}">
                                        <option value="">--</option>
                                        <option value="hour">hour</option>
                                        <option value="day">day</option>
                                        <option value="week" selected>week</option>
                                        <option value="month">month</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>

                <div class="recommendations my-3">
                    <h3>Recommendations</h3>
                    <div class="form-group">
                        <label for="recommendations">Enter some recommendations for the patient to stay healthy</label>
                        <textarea class="form-control" id="recommendations" name="recommendations" rows="3" placeholder="Do not smoke. Exercise 3 times a week."></textarea>
                    </div>
                </div>

                <div class="conditions my-3">
                    <h3>Conditions</h3>
                    <div class="form-group">
                        <label for="conditions">Select which conditions the patient is being treated for</label>
                        <select multiple class="form-control" id="conditions" name="conditions[]">
                            @foreach($conditions as $condition)
                            <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="prescriptions my-3">
                    <h3>Drugs</h3>
                    <p>Select which drugs the patient is currently prescribed</p>
                    @foreach($drugs as $drug)
                    <button class="btn btn-outline-primary m-1" type="button" data-toggle="collapse" data-target="#{{ 'collapse'.$drug->id.'drug' }}" aria-expanded="false" aria-controls="{{ 'collapse'.$drug->id.'drug' }}">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="{{ 'drug'.$drug->id }}" name="{{ 'drug'.$drug->id }}">
                            <label class="form-check-label" for="{{ 'drug'.$drug->id }}">{{ ucfirst($drug->name) }}</label>
                        </div>
                    </button>

                    <div class="collapse" id="{{ 'collapse'.$drug->id.'drug' }}">
                        <div class="card card-body">
                            <h4>
                                {{ ucfirst($drug->name) }}
                            </h4>

                            <h5>Dosage</h5>
                            <div class="d-flex align-items-center">
                                <div class="form-group mr-3">
                                    <label for="{{ 'drug'.$drug->id.'volume' }}">Volume</label>
                                    <input type="number" step=".01" pattern="^\d+(?:\.\d{1,2})?$" class="form-control" id="{{ 'drug'.$drug->id.'volume' }}" name="{{ 'drug'.$drug->id.'volume' }}" placeholder="12.5">
                                </div>

                                <div class="form-group">
                                    <label for="{{ 'drug'.$drug->id.'unit' }}">Unit</label>
                                    <input type="text" class="form-control" id="{{ 'drug'.$drug->id.'unit' }}" name="{{ 'drug'.$drug->id.'unit' }}" placeholder="mg">
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="form-group mr-3">
                                    <label for="{{ 'drug'.$drug->id.'times' }}">Times</label>
                                    <input type="number" class="form-control" id="{{ 'drug'.$drug->id.'times' }}" name="{{ 'drug'.$drug->id.'times' }}" placeholder="3">
                                </div>

                                <div class="form-group">
                                    <label for="{{ 'drug'.$drug->id.'per' }}">Per</label>
                                    <select class="form-control" id="{{ 'drug'.$drug->id.'per' }}" name="{{ 'drug'.$drug->id.'per' }}">
                                        <option value="">--</option>
                                        <option value="hour">hour</option>
                                        <option value="day">day</option>
                                        <option value="week" selected>week</option>
                                        <option value="month">month</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <input type="hidden" name="coordinatorId" value="{{ $coordinator->id }}">

        <div class="d-flex my-5 justify-content-center">
            <div class="mr-3">
                <a href="{{ route('patients.index') }}" class="btn btn-secondary">Cancel</a>
            </div>

            <div class="ml-3">

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i>
                    Create patient profile
                </button>
            </div>
        </div>
    </form>


</div>



@endsection

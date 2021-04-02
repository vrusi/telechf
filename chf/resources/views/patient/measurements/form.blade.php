@extends('layouts.app')

@section('content')

<style>
    .instructions {
        max-width: 300px;
        position: absolute;
        left: 5vw;
        padding: 0 1rem;
    }

    @media(min-width:1400px) {
        .instructions {
            max-width: 400px;
        }
    }

    @media(min-width:1650px) {
        .instructions {
            max-width: 500px;
        }
    }

    @media(min-width:2000px) {
        .instructions {
            left: 10vw;
        }
    }

    @media(min-width:2300px) {
        .instructions {
            left: 15vw;
        }
    }

    @media(min-width:2300px) {
        .instructions {
            left: 15vw;
        }
    }

</style>



<div class="d-none d-xl-block instructions">
    <h2 class="pb-3">
        Instructions
    </h2>
    <div class="text-justify">
        {!! $parameter->instructions !!}
    </div>
</div>



<div class="container patient">

    <h1 class="pb-3">
        Measurement form
    </h1>

    <div class="d-xl-none" x-data="{ instructionsOpen: false }">
        <div @click="instructionsOpen=!instructionsOpen" class="d-flex justify-content-center" data-toggle="collapse" data-target="#instructions" aria-expanded="false" aria-controls="instructions">
            <button class="btn btn-outline-secondary">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        Click to read instructions
                    </div>

                    <i x-show="!instructionsOpen" class="fas fa-caret-down"></i>
                    <i x-show="instructionsOpen" class="fas fa-caret-up"></i>
                </div>
            </button>

        </div>

        <div class="collapse" id="instructions">
            <div class="card card-body">
                {!! $parameter->instructions !!}

            </div>
        </div>
    </div>

    <form method="POST" action="/measurements" class="m-5">
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
        <div class="mb-5">
            <input type="hidden" name="parameter_id" value="{{ $parameter->id }}">
            <div class="form-group">
                <label for="value">{{ $parameter->name }} ({{ $parameter->unit }})</label>
                <input required type="number" step="0.01" class="form-control" name="value" id="value" placeholder="Enter your measurement">
            </div>

            <div class="form-group">
                <label class="mt-3">
                    Rate your swellings
                </label>
                <select required name="swellings" class="form-control">
                    <option value="1">Very good</option>
                    <option value="2">Good</option>
                    <option value="3" selected>Neutral</option>
                    <option value="4">Bad</option>
                    <option value="5">Very bad</option>
                </select>
            </div>

            <div class="form-group">
                <label class="mt-3">
                    Rate your physical exertion tolerance
                </label>
                <select required name="exercise_tolerance" class="form-control">
                    <option value="1">Very good</option>
                    <option value="2">Good</option>
                    <option value="3" selected>Neutral</option>
                    <option value="4">Bad</option>
                    <option value="5">Very bad</option>
                </select>
            </div>


            <div class="form-group">
                <label class="mt-3">
                    Rate your sleeping breathlessness
                </label>
                <select required name="dyspnoea" class="form-control">
                    <option value="1">Very good</option>
                    <option value="2">Good</option>
                    <option value="3" selected>Neutral</option>
                    <option value="4">Bad</option>
                    <option value="5">Very bad</option>
                </select>
            </div>
        </div>

        <div class="row pt-5">
            <div class="col">
                <div class="d-flex d-md-none flex-row fixed-bottom">
                    <button class="btn btn-primary w-50 rounded-0" @click="instructionsRead = !instructionsRead">
                        Back
                    </button>
                    <button type="submit" class="btn btn-primary w-50 rounded-0">
                        Finish
                    </button>
                </div>

                <div class="d-none d-md-flex justify-content-center">
                    <button class="btn btn-secondary mr-3 w-50" @click="instructionsRead = !instructionsRead">
                        Back
                    </button>
                    <button type="submit" class="btn btn-primary w-50">
                        Finish
                    </button>
                </div>
            </div>
        </div>
    </form>



</div>
@endsection

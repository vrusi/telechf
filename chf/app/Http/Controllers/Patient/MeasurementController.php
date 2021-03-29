<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use App\Models\Parameter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Minwork\Helper\Arr;

class MeasurementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date = Carbon::today();

        if ($request->has('date')) {
            $date = Carbon::createFromFormat('Y-m-d', $request->input('date'));
        }

        $user = Auth::user();

        $parameters = $user->parameters->toArray();

        $measurements = Measurement::where('user_id', $user->id)->whereDate('created_at', $date)->get()->toArray();

        $values = array_map(function ($parameter) use ($measurements) {
            $value = null;

            foreach ($measurements as $measurement) {
                if ($measurement['parameter_id'] == $parameter['id']) {
                    $value = $measurement['value'];
                }
            }

            return ['parameter' => $parameter['name'], 'value' => $value, 'unit' => $parameter['unit'], 'date' => $measurement['created_at']];
        }, $parameters);

        $conditions = Arr::map(['swellings' => 'Swellings', 'exercise_tolerance' => 'Exercise Tolerance', 'dyspnoea' => 'Nocturnal Dyspnoea'], function ($key, $name) use ($measurements) {
            $avg = null;

            if (count($measurements) > 0) {
                $avg = 0;
                foreach ($measurements as $measurement) {
                    $avg = $avg + $measurement[$key] ?? 0;
                }
                $avg = $avg / count($measurements);
            }
            
            return ['name' => $name, 'value' => $avg];
        });

        if ($date->isToday()) {
            $next = null;
        } else {
            $next = 'measurements?date=' . $date->copy()->addDays(1)->format('Y-m-d');
        }

        $prev = 'measurements?date=' . $date->copy()->subDays(1)->format('Y-m-d');

        $results = ['measurements' => $values, 'conditions' => array_values($conditions), 'date' => $date, 'previous' => $prev, 'next' => $next];
        return view('patient.measurements.index', $results);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $today = Carbon::now();
        $startOfWeek = $today->startOfWeek();
        $endOfWeek = $today->endOfWeek();

        $user = Auth::user();
        $parameters = $user->parameters;

        $takeToday = [];
        $takeThisWeek = [];

        foreach ($parameters as $parameter) {
            if ($parameter['measurement_span'] == 'week') {
                $count = Measurement::where('user_id', $user->id)
                    ->where('parameter_id', $parameter->id)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])->get()->count();
                if ($count < $parameter->measurement_times) {
                    array_push($takeThisWeek, $parameter);
                }
            } else if ($parameter['measurement_span'] == 'day') {
                $count = Measurement::where('user_id', $user->id)
                    ->where('parameter_id', $parameter->id)
                    ->whereDate('created_at', $today)->get()->count();

                if ($count < $parameter->measurement_times) {
                    array_push($takeToday, $parameter);
                }
            }
        }

        $extra = array_udiff($parameters->toArray(), $takeToday, function ($a, $b) {
            return $a['id'] - $b['id'];
        });

        $extra = array_udiff($extra, $takeThisWeek, function ($a, $b) {
            return $a['id'] - $b['id'];
        });

        $results = ['takeToday' => $takeToday, 'takeThisWeek' => $takeThisWeek, 'extra' => $extra];
        return view('patient.measurements.create', $results);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $now = Carbon::now();
        $validated = $request->validate([
            'parameter_id' => 'required|exists:parameters,id',
            'value' => 'required|numeric',
            'swellings' => 'required|integer|between:1,5',
            'exercise_tolerance' => 'required|integer|between:1,5',
            'dyspnoea' => 'required|integer|between:1,5'
        ]);

        Measurement::create([
            'user_id' => Auth::user()->id,
            'parameter_id' => $validated['parameter_id'],
            'value' => $validated['value'],
            'swellings' => $validated['swellings'],
            'exercise_tolerance' => $validated['exercise_tolerance'],
            'dyspnoea' => $validated['dyspnoea'],
            // TODO
            'triggered_safety_alarm' => false,
            'triggered_therapeutic_alarm' => false,
        ]);

        return redirect('/measurements');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Measurement  $measurement
     * @return \Illuminate\Http\Response
     */
    public function show(Measurement $measurement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Measurement  $measurement
     * @return \Illuminate\Http\Response
     */
    public function edit(Measurement $measurement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Measurement  $measurement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Measurement $measurement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Measurement  $measurement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Measurement $measurement)
    {
        //
    }

    public function measurementForm(Request $request)
    {
        $parameter = Parameter::find($request->route('parameterId'));
        return view('patient.measurements.form', ['parameter' => $parameter]);
    }
}

<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use App\Models\Parameter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Minwork\Helper\Arr;
use phpDocumentor\Reflection\Types\Boolean;
use Ramsey\Uuid\Type\Integer;

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
            $measurement_date = null;
            foreach ($measurements as $measurement) {
                if ($measurement['parameter_id'] == $parameter['id']) {
                    $value = $measurement['value'];
                    $measurement_date = $measurement['created_at'];
                }
            }

            return ['parameter' => $parameter['name'], 'value' => $value, 'unit' => $parameter['unit'], 'date' => $measurement_date];
        }, $parameters);

        $conditions = Arr::map(['swellings' => 'Swellings', 'exercise_tolerance' => 'Exercise Tolerance', 'dyspnoea' => 'Nocturnal Dyspnoea'], function ($key, $name) use ($measurements) {
            $avg = null;

            if (count($measurements) > 0) {
                $avg = 0;

                // some measurements will be null so we have to exclude them 
                // i.e. if the user measures weight, internally weight change is also saved
                // but the conditions will be null to prevent giving weight measurement stronger weight
                // when canculating the average value
                $count = 0;
                foreach ($measurements as $measurement) {
                    $avg = $avg + $measurement[$key] ?? 0;
                    $count = $measurement[$key] ? $count + 1 : $count;
                }
                $avg = $avg / $count;
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
        $validated = $request->validate([
            'parameter_id' => 'required|exists:parameters,id',
            'value' => 'required|numeric',
            'swellings' => 'required|integer|between:1,5',
            'exercise_tolerance' => 'required|integer|between:1,5',
            'dyspnoea' => 'required|integer|between:1,5'
        ]);

        $user = User::where('id', Auth::user()->id)->first();
        $user_parameters = $user->parameters;

        $threshold_safety_min = null;
        $threshold_safety_max = null;
        $threshold_therapeutic_min = null;
        $threshold_therapeutic_max = null;

        // calculate weight change if weight was measured
        $weight_param = Parameter::where('name', 'Weight')->first();
        if ($validated['parameter_id'] == $weight_param->id) {

            // get last weight measurement
            $user_measurements = $user->measurements;
            $weight_prev = null;
            foreach($user_measurements as $measurement) {
                if ($measurement->parameter_id == $weight_param->id){
                    $weight_prev = $measurement->value;
                }
            }

            // calculate weight change
            $weight_change = null;
            if ($weight_prev) {
                $weight_change = $validated['value'] - $weight_prev;
            }

            // save weight change
            if ($weight_change) {
                $weight_change_param = Parameter::where('name', 'Weight Change')->first();

                // check alarms
                foreach ($user_parameters as $parameter) {
                    if ($parameter->id == $weight_change_param->id) {
                        $threshold_safety_min = $parameter->pivot->threshold_safety_min;
                        $threshold_safety_max = $parameter->pivot->threshold_safety_max;
                        $threshold_therapeutic_min = $parameter->pivot->threshold_threshold_min;
                        $threshold_therapeutic_max = $parameter->pivot->threshold_threshold_max;
                    }
                }                

                // insert measurement
                Measurement::create([
                    'user_id' => Auth::user()->id,
                    'parameter_id' => $weight_change_param->id,
                    'value' => $weight_change,
                    'triggered_safety_alarm_min' => $weight_change <= $threshold_safety_min,
                    'triggered_safety_alarm_max' => $weight_change >= $threshold_safety_max,
                    'triggered_therapeutic_alarm_min' => $weight_change <= $threshold_therapeutic_min,
                    'triggered_therapeutic_alarm_max' => $weight_change <= $threshold_therapeutic_max,
                ]);
            }

            // save weight in user table
            $user_model = User::where('id', Auth::user()->id)->first();
            $user_model->weight = $validated['value'];
            $user_model->save();
        }

        // check alarms for the measured parameter
        foreach ($user_parameters as $parameter) {
            if ($parameter->id == $request->parameter_id) {
                $threshold_safety_min = $parameter->pivot->threshold_safety_min;
                $threshold_safety_max = $parameter->pivot->threshold_safety_max;
                $threshold_therapeutic_min = $parameter->pivot->threshold_threshold_min;
                $threshold_therapeutic_max = $parameter->pivot->threshold_threshold_max;
            }
        }

        // insert measurement
        Measurement::create([
            'user_id' => Auth::user()->id,
            'parameter_id' => $validated['parameter_id'],
            'value' => $validated['value'],
            'swellings' => $validated['swellings'],
            'exercise_tolerance' => $validated['exercise_tolerance'],
            'dyspnoea' => $validated['dyspnoea'],
            'triggered_safety_alarm_min' => $validated['value'] <= $threshold_safety_min,
            'triggered_safety_alarm_max' => $validated['value'] >= $threshold_safety_max,
            'triggered_therapeutic_alarm_min' => $validated['value'] <= $threshold_therapeutic_min,
            'triggered_therapeutic_alarm_max' => $validated['value'] <= $threshold_therapeutic_max,
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

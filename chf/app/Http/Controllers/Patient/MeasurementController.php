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
            $measurementDate = null;
            foreach ($measurements as $measurement) {
                if ($measurement['parameter_id'] == $parameter['id']) {
                    $value = $measurement['value'];
                    $measurementDate = $measurement['created_at'];
                }
            }

            return ['parameter' => $parameter['name'], 'value' => $value, 'unit' => $parameter['unit'], 'date' => $measurementDate];
        }, $parameters);

        $conditions = Arr::map(['swellings' => 'Swellings', 'exercise_tolerance' => 'Exercise Tolerance', 'dyspnoea' => 'Dyspnoea while lying down'], function ($key, $name) use ($measurements) {
            $avg = null;

            if (count($measurements) > 0) {
                $avg = 0;
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

        $startOfWeek = $today->copy()->startOfWeek();
        $endOfWeek = $today->copy()->endOfWeek();

        $user = Auth::user();

        $parameters = [];
        $parametersRaw = $user->parameters;
        foreach ($parametersRaw as $parameter) {
            if ($parameter['fillable']) {
                array_push($parameters, $parameter);
            }
        }

        $takeToday = [];
        $takeThisWeek = [];

        foreach ($parameters as $parameter) {
            if ($parameter->pivot->measurement_times == null || $parameter->pivot->measurement_span == null) {
                array_push($takeToday, $parameter);
            }
            
            if ($parameter->pivot->measurement_span == 'week') {
                $count = Measurement::where('user_id', $user->id)
                    ->where('parameter_id', $parameter->id)
                    ->whereBetween('created_at', [$startOfWeek->copy(), $endOfWeek->copy()])->get()->count();

                if ($count < $parameter->pivot->measurement_times) {
                    array_push($takeThisWeek, $parameter);
                }

            } else if ($parameter->pivot->measurement_span == 'day') {
                $count = Measurement::where('user_id', $user->id)
                    ->where('parameter_id', $parameter->id)
                    ->whereDate('created_at', $today->copy())->get()->count();

                if ($count < $parameter->pivot->measurement_times) {
                    array_push($takeToday, $parameter);
                }
            }
        }

        $extra = array_udiff($parameters, $takeToday, function ($a, $b) {
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
            'dyspnoea' => 'required|integer|between:1,5',
            'extraDescription' => 'nullable|string',
        ]);

        $user = User::where('id', Auth::user()->id)->first();
        $userParameters = $user->parameters;
        $parameters = Parameter::orderBy('id', 'ASC')->get();

        $thresholdSafetyMin = null;
        $thresholdSafetyMax = null;
        $thresholdTherapeuticMin = null;
        $thresholdTherapeuticMax = null;

        // calculate weight change if weight was measured
        $weightParam = Parameter::where('name', 'Weight')->first();
        $weightChangeParamId = null;
        $weightChange = null;
        $thresholdSafetyMinWeightChange = null;
        $thresholdSafetyMaxWeightChange = null;
        $thresholdTherapeuticMinWeightChange = null;
        $thresholdTherapeuticMaxWeightChange = null;

        if ($validated['parameter_id'] == $weightParam->id) {

            // get last weight measurement
            $userMeasurements = $user->measurements;
            $weightPrevious = null;
            foreach ($userMeasurements as $measurement) {
                if ($measurement->parameter_id == $weightParam->id) {
                    $weightPrevious = $measurement->value;
                }
            }

            // calculate weight change
            if ($weightPrevious) {
                $weightChange = $validated['value'] - $weightPrevious;
            }

            // save weight change
            if ($weightChange) {
                $weightChangeParame = Parameter::where('name', 'Weight Change')->first();
                $weightChangeParamId = $weightChangeParame->id;

                // check personal threshold alarms
                foreach ($userParameters as $parameter) {
                    if ($parameter->id == $weightChangeParamId) {
                        $thresholdSafetyMinWeightChange = $parameter->pivot->threshold_safety_min;
                        $thresholdSafetyMaxWeightChange = $parameter->pivot->threshold_safety_max;
                        $thresholdTherapeuticMinWeightChange = $parameter->pivot->threshold_therapeutic_min;
                        $thresholdTherapeuticMaxWeightChange = $parameter->pivot->threshold_therapeutic_max;
                    }
                }

                // check global threshold alarms
                foreach ($parameters as $parameter) {
                    if ($parameter->id == $weightChangeParamId) {
                        $thresholdSafetyMinWeightChange = $thresholdSafetyMinWeightChange ? $thresholdSafetyMinWeightChange : $parameter->threshold_min;
                        $thresholdSafetyMaxWeightChange = $thresholdSafetyMaxWeightChange ? $thresholdSafetyMaxWeightChange : $parameter->threshold_max;
                    }
                }
            }

            // save weight in user table
            $user_model = User::where('id', Auth::user()->id)->first();
            $user_model->weight = $validated['value'];
            $user_model->save();
        }

        // check personal threshold alarms for the measured parameter
        foreach ($userParameters as $parameter) {
            if ($parameter->id == $request->parameter_id) {
                $thresholdSafetyMin = $parameter->pivot->threshold_safety_min;
                $thresholdSafetyMax = $parameter->pivot->threshold_safety_max;
                $thresholdTherapeuticMin = $parameter->pivot->threshold_therapeutic_min;
                $thresholdTherapeuticMax = $parameter->pivot->threshold_therapeutic_max;
            }
        }

        // check global threshold alarms
        foreach ($parameters as $parameter) {
            if ($parameter->id == $request->parameter_id) {
                $thresholdSafetyMin = $thresholdSafetyMin ? $thresholdSafetyMin : ($parameter->threshold_min ?? null);
                $thresholdSafetyMax = $thresholdSafetyMax ? $thresholdSafetyMax : ($parameter->threshold_max ?? null);
            }
        }

        $extra = $request->query('extra');

        // insert measurement
        Measurement::create([
            'user_id' => Auth::user()->id,
            'parameter_id' => $validated['parameter_id'],
            'value' => $validated['value'],
            'swellings' => $validated['swellings'],
            'exercise_tolerance' => $validated['exercise_tolerance'],
            'dyspnoea' => $validated['dyspnoea'],
            'triggered_safety_alarm_min' => $thresholdSafetyMin ? $validated['value'] <= $thresholdSafetyMin : false,
            'triggered_safety_alarm_max' => $thresholdSafetyMax ? $validated['value'] >= $thresholdSafetyMax : false,
            'triggered_therapeutic_alarm_min' => $thresholdTherapeuticMin ? $validated['value'] <= $thresholdTherapeuticMin : false,
            'triggered_therapeutic_alarm_max' => $thresholdTherapeuticMax ? $validated['value'] >= $thresholdTherapeuticMax : false,
            'extra' => $extra == "1" ? true : false,
            'extra_description' => $validated['extraDescription'] ?? null,
        ]);


        // if measurement is weight, save weight change too
        if ($validated['parameter_id'] == $weightParam->id && $weightChange) {
            Measurement::create([
                'user_id' => Auth::user()->id,
                'parameter_id' => $weightChangeParamId,
                'value' => $weightChange,
                'triggered_safety_alarm_min' => abs($weightChange) <= abs($thresholdSafetyMinWeightChange),
                'triggered_safety_alarm_max' => abs($weightChange) >= abs($thresholdSafetyMaxWeightChange),
                'triggered_therapeutic_alarm_min' => abs($weightChange) <= abs($thresholdTherapeuticMinWeightChange),
                'triggered_therapeutic_alarm_max' => abs($weightChange) >= abs($thresholdTherapeuticMaxWeightChange),
                'extra' => $extra == "1" ? true : false,
                'extra_description' => $validated['extraDescription'] ?? null,
            ]);
        }

        return redirect('/dashboard');
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
        $extra = $request->query('extra');
        $parameter = Parameter::find($request->route('parameterId'));
        $locale = $request->getPreferredLanguage(['en', 'sk']);
        return view('patient.measurements.form', ['parameter' => $parameter, 'extra' => $extra, 'locale' => $locale]);
    }
}

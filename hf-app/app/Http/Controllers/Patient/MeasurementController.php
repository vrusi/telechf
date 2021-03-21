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

        $parameters = Parameter::all()->toArray();

        $measurements = Measurement::where('user_id', Auth::user()->id)->whereDate('created_at', $date)->get()->toArray();

        $values = array_map(function ($parameter) use ($measurements) {
            $value = null;

            foreach ($measurements as $measurement) {
                if ($measurement['parameter_id'] == $parameter['id']) {
                    $value = $measurement['value'];
                }
            }

            return ['parameter' => $parameter['name'], 'value' => $value, 'unit' => $parameter['unit']];
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
}

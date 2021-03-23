<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use Illuminate\Http\Request;
use Illuminate\Support\Arr as SupportArr;
use Illuminate\Support\Facades\Auth;
use Minwork\Helper\Arr;

class DashboardController extends Controller
{
    function index(Request $request)
    {
        $user = Auth::user();
        $parameters = $user->parameters->toArray();

        $measurementsGrouped = Measurement::where('user_id', $user->id)->orderBy('created_at', 'desc')->get()->groupBy(function ($measurement) {
            return $measurement->created_at->format('d M');
        })->toArray();

        $days = array_map(function ($measurementsPerDay) use ($parameters) {
            $values = array_map(function ($parameter) use ($measurementsPerDay) {
                $value = null;
                $safety_alarm = false;
                $therapeutic_alarm = false;


                foreach ($measurementsPerDay as $measurement) {
                    if ($measurement['parameter_id'] == $parameter['id']) {
                        $value = $measurement['value'];
                        $therapeutic_alarm = $measurement['triggered_therapeutic_alarm'];
                        $safety_alarm = $measurement['triggered_safety_alarm'];
                    }
                }
                return ['parameter' => $parameter['name'], 'value' => $value, 'unit' => $parameter['unit'], 'triggered_safety_alarm' => $safety_alarm, 'triggered_therapeutic_alarm' => $therapeutic_alarm];
            }, $parameters);

            $conditions = Arr::map(['swellings' => 'Swellings', 'exercise_tolerance' => 'Exercise Tolerance', 'dyspnoea' => 'Nocturnal Dyspnoea'], function ($key, $name) use ($measurementsPerDay) {
                $avg = null;

                if (count($measurementsPerDay) > 0) {
                    $avg = 0;
                    foreach ($measurementsPerDay as $measurement) {
                        $avg = $avg + $measurement[$key] ?? 0;
                    }
                    $avg = $avg / count($measurementsPerDay);
                }

                return ['name' => $name, 'value' => $avg];
            });
            $conditions = array_values($conditions);
            return array_merge($values, $conditions);
        }, $measurementsGrouped);

        return view('patient.dashboard.index', ['summary' => $days, 'parameters' => $parameters]);
    }
}

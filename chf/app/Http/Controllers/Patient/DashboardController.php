<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr as SupportArr;
use Illuminate\Support\Facades\Auth;
use Minwork\Helper\Arr;

class DashboardController extends Controller
{
    private function mapConditions(int $value)
    {
        if ($value == 5) {
            return 'Very bad';
        } else if ($value == 4) {
            return 'Bad';
        } else if ($value == 3) {
            return 'Neutral';
        } else if ($value == 2) {
            return 'Good';
        } else if ($value == 1) {
            return 'Very good';
        }

        return null;
    }


    function index(Request $request)
    {
        $user = Auth::user();
        $parameters = $user->parameters->toArray();

        $measurementsGrouped = Measurement::where('user_id', $user->id)->orderBy('created_at', 'desc')->get()->groupBy(function ($measurement) {
            return $measurement->created_at->format('d M');
        })->toArray();

        // TODO
        $alarmGeneral = false;

        $daysSummary = array_map(function ($measurementsPerDay) use ($parameters) {
            $values = array_map(function ($parameter) use ($measurementsPerDay) {
                $value = null;
                $alarm = false;

                foreach ($measurementsPerDay as $measurement) {
                    if ($measurement['parameter_id'] == $parameter['id']) {
                        $value = $measurement['value'];
                        $alarm = $measurement['triggered_therapeutic_alarm_min'] || $measurement['triggered_therapeutic_alarm_max'] ||  $measurement['triggered_safety_alarm_min'] || $measurement['triggered_safety_alarm_max'];

                        if ($alarm) {
                            $alarmGeneral = true;
                        }
                    }
                }

                return ['parameter' => $parameter['name'], 'value' => $value, 'unit' => $parameter['unit'], 'alarm' => $alarm, 'date' => $measurement['created_at']];
            }, $parameters);

            $conditions = Arr::map(['swellings' => 'Swellings', 'exercise_tolerance' => 'Exercise Tolerance', 'dyspnoea' => 'Nocturnal Dyspnoea'], function ($key, $name) use ($measurementsPerDay) {
                $avg = null;
                $avgMapped = '';
                // TODO
                $alarm = false;

                if (count($measurementsPerDay) > 0) {
                    $avg = 0;
                    foreach ($measurementsPerDay as $measurement) {
                        $avg = $avg + $measurement[$key] ?? 0;
                    }
                    $avg = $avg / count($measurementsPerDay);

                    $avgMapped = $this->mapConditions(ceil($avg));
                }

                return ['name' => $name, 'value' => $avgMapped, 'alarm' => $alarm];
            });

            $conditions = array_values($conditions);

            return array_merge($values, $conditions);
        }, $measurementsGrouped);

        $daysAlarms = array();
        foreach($daysSummary as $date => $day) {
            foreach ($day as $measurement) {

                if (!$measurement['alarm']) {
                    continue;
                }

                if (in_array($day, $daysAlarms)) {
                    continue;
                }

                $daysAlarms[$date] = $day;
            }
        }

        return view('patient.dashboard.index', ['summary' => $daysSummary, 'alarms' => $daysAlarms, 'parameters' => $parameters]);
    }
}

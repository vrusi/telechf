<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\ECG;
use App\Models\Measurement;
use App\Models\Parameter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChartController extends Controller
{
    function index(Request $request)
    {
        ini_set('memory_limit', '-1');

        $filterOption = $request->has('filter') ? $request->input('filter') : "5";

        $user = Auth::user();
        $thresholds = $user->thresholds();

        $measurements = Measurement::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
        $parameters = $user->parameters;

        $charts = array();

        foreach ($parameters as $parameter) {
            if (strtolower($parameter['name']) == 'ecg') {
                continue;
            }
            $name = $parameter->name;
            $unit = $parameter->unit;

            $values = array();
            $dates = array();

            foreach ($measurements as $measurement) {
                // last week's data
                if ($filterOption == "1") {
                    $lastWeek = Carbon::now()->copy()->subWeek();
                    if ($measurement->created_at->gte($lastWeek)) {
                        if ($measurement->parameter_id == $parameter->id) {
                            array_push($values, $measurement->value);
                            array_push($dates, $measurement->created_at);
                        }
                    }
                    // last month's data
                } else if ($filterOption == "2") {

                    $lastMonth = Carbon::now()->copy()->subMonth();
                    if ($measurement->created_at->gte($lastMonth)) {
                        if ($measurement->parameter_id == $parameter->id) {
                            array_push($values, $measurement->value);
                            array_push($dates, $measurement->created_at);
                        }
                    }

                    // last three months' data
                } else if ($filterOption == "3") {
                    $threeMonthsAgo = Carbon::now()->copy()->subMonths(3);
                    if ($measurement->created_at->gte($threeMonthsAgo)) {
                        if ($measurement->parameter_id == $parameter->id) {
                            array_push($values, $measurement->value);
                            array_push($dates, $measurement->created_at);
                        }
                    }

                    // last six months' data
                } else if ($filterOption == "4") {
                    $sixMonthsAgo = Carbon::now()->copy()->subMonths(6);
                    if ($measurement->created_at->gte($sixMonthsAgo)) {
                        if ($measurement->parameter_id == $parameter->id) {
                            array_push($values, $measurement->value);
                            array_push($dates, $measurement->created_at);
                        }
                    }

                    // all data
                } else if ($filterOption == "5") {
                    if ($measurement->parameter_id == $parameter->id) {
                        array_push($values, $measurement->value);
                        array_push($dates, $measurement->created_at);
                    }
                }
            }

            array_push(
                $charts,
                [
                    'name' => $name,
                    'unit' => $unit,
                    'values' => $values,
                    'dates' => $dates,
                    'max_therapeutic' => $thresholds[$parameter->id]['therapeuticMax'],
                    'min_therapeutic' => $thresholds[$parameter->id]['therapeuticMin']
                ]
            );

            unset($values);
            unset($dates);
        }

        // get ECG data
        $ecgData = ECG::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();

        $ecgParam = Parameter::where('name', 'ECG')->first();
        $chartsECG = array();
        foreach ($ecgData as $dataPoint) {
            $ecgValuesRaw = explode(',', $dataPoint['values']);

            $ecgDates = array();
            $ecgValues = array();

            $startDate = $dataPoint['created_at']->copy();

            for ($i = 0; $i < count($ecgValuesRaw); $i++) {
                array_push($ecgDates, $i);
                array_push($ecgValues, round(intval($ecgValuesRaw[$i])/1000, 2));
            }

            array_push($chartsECG, [
                'id' => $dataPoint['id'],
                'name' => $ecgParam->name,
                'unit' => $ecgParam->unit,
                'values' =>  $ecgValues,
                'dates' => $ecgDates,
                'eventsP' => explode(',', $dataPoint['eventsP']),
                'eventsB' => explode(',', $dataPoint['eventsB']),
                'eventsT' => explode(',', $dataPoint['eventsT']),
                'eventsAF' => explode(',', $dataPoint['eventsAF']),
            ]);

            break;
        }

        $chartsECGEncoded = json_encode($chartsECG, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_NUMERIC_CHECK);

        return view('patient.charts.index', [
            'charts' => $charts,
            'charts_encoded' => json_encode($charts, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_NUMERIC_CHECK),
            'filterOption' => $filterOption,
            'chartsECG' => $chartsECG,
            'chartsECG_encoded' => $chartsECGEncoded,
        ]);
    }

    function filter(Request $request)
    {
        $filterOption = $request->filterOption;
        return redirect()->action([ChartController::class, 'index'], ['filter' => $filterOption]);
    }
}

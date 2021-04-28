<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\ECG;
use App\Models\Measurement;
use App\Models\Parameter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    function index(Request $request)
    {
        ini_set('memory_limit', '-1');

        $filterOption = $request->has('filter') ? $request->input('filter') : "5";
        $chosenEcgDate = $request->has('chosenEcgDate') ? Carbon::parse($request->chosenEcgDate) : null;

        $patient = User::where('id', $request->route('patient'))->first();

        $thresholds = $patient->thresholds();

        $measurements = Measurement::where('user_id', $patient->id)->orderBy('created_at', 'DESC')->get();
        $parameters = $patient->parameters()->orderBy('id', 'ASC')->get();

        $charts = array();

        foreach ($parameters as $parameter) {
            $name = $parameter->name;
            if (strtolower($name) == 'ecg') {
                continue;
            }

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
                    'min_therapeutic' => $thresholds[$parameter->id]['therapeuticMin'],
                    'max_safety' => $thresholds[$parameter->id]['safetyMax'],
                    'min_safety' => $thresholds[$parameter->id]['safetyMin'],
                ]

            );

            unset($values);
            unset($dates);
        }

        // get conditions data
        $conditions = $patient->conditionsAveragesByDay();
        $conditionsDates = array();
        $swellingsValues = array();
        $exerciseValues = array();
        $dyspnoeaValues = array();
        foreach($conditions as $date => $conditionsInDay) {
            array_push($conditionsDates, Carbon::parse($date));
            array_push($swellingsValues, round($conditionsInDay['swellings'], 2));
            array_push($exerciseValues, round($conditionsInDay['exercise'], 2));
            array_push($dyspnoeaValues, round($conditionsInDay['dyspnoea'], 2));
        }

        $conditionsParsed = [
            'dates' => $conditionsDates,
            'swellings' => $swellingsValues,
            'exercise' => $exerciseValues,
            'dyspnoea' => $dyspnoeaValues,
        ];

        // get available ECG data dates
        $ecgAvailableDatesRaw = ECG::where('user_id', $patient->id)->orderBy('created_at', 'DESC')->orderBy('updated_at', 'DESC')->pluck('created_at');
        $ecgAvailableDates = array();
        foreach ($ecgAvailableDatesRaw as $ecgAvailableDate) {
            array_push(
                $ecgAvailableDates,
                [
                    'date' => $ecgAvailableDate,
                    'dateFormatted' => $ecgAvailableDate->format('d M Y, H:i:s'),
                ]
            );
        }

        // get ECG data
        $ecgData = $chosenEcgDate
            ? ECG::where('user_id', $patient->id)->whereDate('created_at', $chosenEcgDate)->first()
            : ECG::where('user_id', $patient->id)->orderBy('created_at', 'DESC')->orderBy('updated_at', 'DESC')->first();

        if (!$ecgData) {
            return view('coordinator.patients.charts.index', [
                'patient' => $patient,
                'charts' => $charts,
                'charts_encoded' => json_encode($charts, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_NUMERIC_CHECK),
                'filterOption' => $filterOption,
                'chartECG' => null,
                'chartECG_encoded' => json_encode(false, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_NUMERIC_CHECK),
                'ecgAvailableDates' => $ecgAvailableDates,
            ]);
        }

        $ecgParam = Parameter::where('name', 'ECG')->first();
        $ecgValuesRaw = explode(',', $ecgData['values']);

        $ecgDates = array();
        $ecgValues = array();

        for ($i = 0; $i < count($ecgValuesRaw); $i++) {
            array_push($ecgDates, $i);
            array_push($ecgValues, round(intval($ecgValuesRaw[$i]) / 1000, 2));
        }

        $chartECG = [
            'id' => $ecgData['id'],
            'name' => $ecgParam->name,
            'unit' => $ecgParam->unit,
            'values' =>  $ecgValues,
            'dates' => $ecgDates,
            'date' => $ecgData['created_at']->format('d M Y, H:i:s'),
            'eventsP' => explode(',', $ecgData['eventsP']),
            'eventsB' => explode(',', $ecgData['eventsB']),
            'eventsT' => explode(',', $ecgData['eventsT']),
            'eventsAF' => explode(',', $ecgData['eventsAF']),
        ];

        return view(
            'coordinator.patients.charts.index',
            [
                'patient' => $patient,
                'charts' => $charts,
                'charts_encoded' => json_encode($charts, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_NUMERIC_CHECK),
                'filterOption' => $filterOption,
                'conditions' => $conditionsParsed,
                'conditions_encoded' => json_encode($conditionsParsed, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_NUMERIC_CHECK),
                'chartECG' => $chartECG,
                'chartECG_encoded' => json_encode($chartECG, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_NUMERIC_CHECK),
                'ecgAvailableDates' => $ecgAvailableDates,
            ]
        );
    }

    function filter(Request $request)
    {
        $patient = User::where('id', $request->route('patient'))->first();
        $filterOption = $request->filterOption;
        return redirect()->action(
            [
                ChartController::class,
                'index'
            ],
            [
                'patient' => $patient->id,
                'filter' => $filterOption
            ]
        );
    }

    function selectDate(Request $request)
    {
        $patient = User::where('id', $request->route('patient'))->first();
        $chosenEcgDate = $request->ecgDateChoice ? $request->ecgDateChoice : null;

        return redirect()->action(
            [
                ChartController::class,
                'index'
            ],
            [
                'patient' => $patient->id,
                'chosenEcgDate' => $chosenEcgDate,
            ]
        );
    }
}

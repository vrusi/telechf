<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\ECG;
use App\Models\Measurement;
use App\Models\Parameter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChartController extends Controller
{
    function index(Request $request)
    {
        ini_set('memory_limit', '-1');

        $filterOption = $request->has('filter') ? $request->input('filter') : "5";
        $chosenEcgDate = $request->has('chosenEcgDate') ? Carbon::parse($request->chosenEcgDate) : null;
        $chosenConditionsDate = $request->has('chosenConditionsDate') ? Carbon::parse($request->chosenConditionsDate) : null;

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

        // get conditions data

        $conditions = $user->conditionsCountsByDay();
        $conditionsDates = array();
        $swellingsValues = array();
        $exerciseValues = array();
        $dyspnoeaValues = array();
        $entryDate = null;
        $hasOneEntry = false;
        foreach ($conditions as $date => $conditionsInDay) {
            $parsedDate = Carbon::parse($date);
            // if a date was chosen, take the corresponding measurements
            if ($chosenConditionsDate) {
                if ($parsedDate->isSameDay($chosenConditionsDate)) {
                    array_push($swellingsValues, $conditionsInDay['swellings']);
                    array_push($exerciseValues, $conditionsInDay['exercise']);
                    array_push($dyspnoeaValues, $conditionsInDay['dyspnoea']);
                    $entryDate = $parsedDate->copy()->format('d M Y');
                }
            }
            // else take the latest measurement
            else {
                if (!$hasOneEntry) {
                    array_push($swellingsValues, $conditionsInDay['swellings']);
                    array_push($exerciseValues, $conditionsInDay['exercise']);
                    array_push($dyspnoeaValues, $conditionsInDay['dyspnoea']);
                    $hasOneEntry = true;
                    $entryDate = $parsedDate->copy()->format('d M Y');
                }
            }

            array_push($conditionsDates, $parsedDate);
        }

        $conditionsParsed = [
            'dates' => $conditionsDates,
            'date' => $entryDate,
            'swellings' => $swellingsValues,
            'exercise' => $exerciseValues,
            'dyspnoea' => $dyspnoeaValues,
        ];

        // get available ECG data dates
        $ecgAvailableDatesRaw = ECG::where('user_id', $user->id)->orderBy('created_at', 'DESC')->orderBy('updated_at', 'DESC')->pluck('created_at');
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
            ? ECG::where('user_id', $user->id)->where('created_at', $chosenEcgDate)->first()
            : ECG::where('user_id', $user->id)->orderBy('created_at', 'DESC')->orderBy('updated_at', 'DESC')->first();

        if (!$ecgData) {
            return view('coordinator.patients.charts.index', [
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
        $ecgDatesMs = array();
        $ecgValues = array();
        $currentDate = $ecgData['created_at'];
        for ($i = 0; $i < count($ecgValuesRaw); $i++) {
            array_push($ecgDatesMs, $i);
            array_push($ecgDates, $currentDate->copy()->addMilliseconds($i));
            array_push($ecgValues, round(intval($ecgValuesRaw[$i]) / 1000, 2));
        }

        $eventsP = explode(',', $ecgData['eventsP']);
        $eventsB = explode(',', $ecgData['eventsB']);
        $eventsT = explode(',', $ecgData['eventsT']);
        $eventsAF = explode(',', $ecgData['eventsAF']);

        $eventsPSegments = array();
        $eventsBSegments = array();
        $eventsTSegments = array();
        $eventsAFSegments = array();

        $eventsMerged = [$eventsP, $eventsB, $eventsT, $eventsAF];
        foreach ($eventsMerged as $indexEvents => $events) {

            $eventsLength = count($events);
            $isEmpty = $eventsLength == 0 || ($eventsLength == 1 && !$events[0]);
            if ($isEmpty) {
                continue;
            }
            $segmentStart = null;
            $current = null;
            // $event is the position in time
            foreach ($events as $index => $event) {

                // if start was unassigned, start is now
                if ($segmentStart == null) {
                    $segmentStart = $ecgData['created_at']->copy()->addMilliseconds($event);
                    $current = $segmentStart->copy();
                    continue;
                }

                $willOverflow = $eventsLength - 1 == $index;
                $valueAhead = $ecgData['created_at']->copy()->addMilliseconds($events[$index]);
                // if next event is one ms away, it's a part of the current event segment
                if (!$willOverflow && ($valueAhead->eq($current->copy()->addMillisecond()))) {
                    $current->addMillisecond();
                }
                // if this is the last item in the array
                // or if next event is more than a ms away,
                // the current event marks the end of the segment
                else {
                    if ($indexEvents == 0) {
                        array_push($eventsPSegments, ['start' => $segmentStart, 'end' => $current]);
                    } elseif ($indexEvents == 1) {
                        array_push($eventsBSegments, ['start' => $segmentStart, 'end' => $current]);
                    } elseif ($indexEvents == 2) {
                        array_push($eventsTSegments, ['start' => $segmentStart, 'end' => $current]);
                    } elseif ($indexEvents == 3) {
                        array_push($eventsAFSegments, ['start' => $segmentStart, 'end' => $current]);
                    }

                    $segmentStart = null;
                    $current = null;
                }
            }
        }

        $chartECG = [
            'id' => $ecgData['id'],
            'name' => $ecgParam->name,
            'unit' => $ecgParam->unit,
            'values' =>  $ecgValues,
            'dates' => $ecgDates,
            'datesMs' => $ecgDatesMs,
            'date' => $ecgData['created_at']->format('d M Y, H:i:s'),
            'eventsP' => $eventsPSegments,
            'eventsB' => $eventsBSegments,
            'eventsT' => $eventsTSegments,
            'eventsAF' => $eventsAFSegments,
        ];

        return view(
            'patient.charts.index',
            [
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
        $filterOption = $request->filterOption;
        return redirect()->action([ChartController::class, 'index'], ['filter' => $filterOption]);
    }

    function selectDate(Request $request)
    {
        $chosenEcgDate = $request->ecgDateChoice ? $request->ecgDateChoice : null;
        $chosenConditionsDate = $request->conditionsDateChoice ? $request->conditionsDateChoice : null;

        return redirect()->action([ChartController::class, 'index'], [
            'chosenEcgDate' => $chosenEcgDate,
            'chosenConditionsDate' => $chosenConditionsDate,
            ]);
    }
}

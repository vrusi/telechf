<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    function index(Request $request)
    {
        $patient = User::where('id', $request->route('patient'))->first();

        $thresholds = $patient->thresholds();

        $measurements = $patient->measurements;
        $parameters = $patient->parameters;

        $charts = array();

        foreach ($parameters as $parameter) {
            $name = $parameter->name;
            $unit = $parameter->unit;

            $values = array();
            $dates = array();

            foreach ($measurements as $measurement) {
                if ($measurement->parameter_id == $parameter->id) {
                    array_push($values, $measurement->value);
                    array_push($dates, $measurement->created_at);
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
                'min_safety' => $thresholds[$parameter->id]['safetyMin']]

            );

            unset($values);
            unset($dates);
        }

        return view('coordinator.patients.charts.index', ['patient' => $patient, 'charts' => $charts, 'charts_encoded' => json_encode($charts, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_NUMERIC_CHECK)]);
    }
}

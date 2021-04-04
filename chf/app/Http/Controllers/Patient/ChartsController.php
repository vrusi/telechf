<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChartsController extends Controller
{
    function index(Request $request)
    {
        $user = Auth::user();
        $thresholds = $user->thresholds();

        $measurements = $user->measurements;
        $parameters = $user->parameters;

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
                'max' => $thresholds[$parameter->id]['therapeuticMax'],
                'min' => $thresholds[$parameter->id]['therapeuticMin']]
            );

            unset($values);
            unset($dates);
        }
        
        dump($thresholds);
        return view('patient.charts.index', ['charts' => $charts, 'charts_encoded' => json_encode($charts, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_NUMERIC_CHECK)]);
    }
}

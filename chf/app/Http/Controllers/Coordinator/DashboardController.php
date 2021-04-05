<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use App\Models\Parameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $patients = $user->patients;
        $parameters = Parameter::all();

        $alarms = array();

        foreach ($patients as $patient) {
            array_push($alarms, $patient->measurementsAlarms());
        }

        $measurementsWithAlarms = Measurement::with('user')
            ->where('triggered_safety_alarm_max', '=', true)
            ->orWhere('triggered_safety_alarm_min', '=', true)
            ->orWhere('triggered_therapeutic_alarm_max', '=', true)
            ->orWhere('triggered_therapeutic_alarm_min', '=', true)
            ->orderBy('created_at')
            ->get();

        $days = array();
        // for each measurement, get the rest of the patient's measurements in that day
        foreach ($measurementsWithAlarms as $measurementWithAlarm) {
            foreach ($patients as $patient) {

                if ($measurementWithAlarm->user->id == $patient->id) {

                    $measurementsInDay = $patient->measurementsInDay($measurementWithAlarm->created_at);

                    // fill the rest of the parameters with nulls
                    $measurementsInDayPadded = array_fill(1, count($parameters) - 1, null);
                    foreach ($parameters as $parameter) {
                        foreach ($measurementsInDay as $measurementInDay) {
                            
                            if (!is_array($measurementInDay)) {
                                $measurementInDay = $measurementInDay->toArray();
                            }

                            if (array_key_exists('parameter_id', $measurementInDay)) {
                                if ($measurementInDay['parameter_id'] == $parameter->id) {
                                    $measurementsInDayPadded[$parameter->id] = $measurementInDay;
                                }
                            }
                        }
                    }
                  
                    // conditions averages
                    array_push($measurementsInDayPadded, $measurementsInDay[count($measurementsInDay) - 3]);
                    array_push($measurementsInDayPadded, $measurementsInDay[count($measurementsInDay) - 2]);
                    array_push($measurementsInDayPadded, $measurementsInDay[count($measurementsInDay) - 1]);

                    array_push($days, [
                        'measurements' => $measurementsInDayPadded,
                        'date' => $measurementWithAlarm->created_at->format('d M'),
                        'patient' => $patient,
                    ]);
                }
            }
        }

        return view('coordinator.dashboard.index', [
            'parameters' => $parameters,
            'alarms' => $days,
        ]);
    }
}

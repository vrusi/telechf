<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use App\Models\Parameter;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $patients = $user->patients;
        $parameters = Parameter::orderBy('id', 'ASC')->get();

        $alarms = array();

        foreach ($patients as $patient) {
            array_push($alarms, $patient->measurementsAlarms());
        }

        $measurementsWithAlarms = Measurement::with('user')
            ->where('triggered_safety_alarm_max', '=', true)
            ->orWhere('triggered_safety_alarm_min', '=', true)
            ->orWhere('triggered_therapeutic_alarm_max', '=', true)
            ->orWhere('triggered_therapeutic_alarm_min', '=', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $days = array();
       //$checkedPatients = array();

        // for each measurement, get the rest of the patient's measurements in that day
        foreach ($measurementsWithAlarms as $measurementWithAlarm) {
            foreach ($patients as $patient) {
                if ($measurementWithAlarm->user->id == $patient->id) {

                    // check if day has been checked
                    //$isPatientInChecked = array_key_exists($patient->id, $checkedPatients);
                    //$isDateInChecked = in_array($measurementWithAlarm->created_at->toDateString(), $checkedPatients[$patient->id]);



                        $measurementsInDay = $patient->measurementsInDay($measurementWithAlarm->created_at);

                        // fill the array with nulls for proper sorting in the table
                        $measurementsInDayPadded = array_fill(1, count($parameters) - 1, null);
                        $anyUnchecked = false;
                        foreach ($parameters as $parameter) {
                            foreach ($measurementsInDay as $measurementInDay) {

                                if (!is_array($measurementInDay)) {
                                    $measurementInDay = $measurementInDay->toArray();
                                }

                                // fill the values in their respective places
                                if (array_key_exists('parameter_id', $measurementInDay)) {
                                    if ($measurementInDay['parameter_id'] == $parameter->id) {
                                        $measurementsInDayPadded[$parameter->id] = $measurementInDay;
                                        $anyUnchecked = $anyUnchecked ? $anyUnchecked : !$measurementInDay['checked'];
                                    }
                                }

                                // mark day as checked
                                //$checkedPatients[$patient->id] = $measurementInDay['created_at'];
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
                            'anyUnchecked' => $anyUnchecked, 
                        ]);

                }
            }
        }

        //dd($days);

        return view('coordinator.dashboard.index', [
            'parameters' => $parameters,
            'alarms' => $days,
        ]);
    }
}

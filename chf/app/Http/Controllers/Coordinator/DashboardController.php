<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use App\Models\Parameter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /*  public function index()
    {
        $user = Auth::user();
        $patients = $user->patients;
        $parameters = Parameter::orderBy('id', 'ASC')->get();

        $measurementsWithAlarms = Measurement::with('user')
            ->where('triggered_safety_alarm_max', '=', true)
            ->orWhere('triggered_safety_alarm_min', '=', true)
            ->orWhere('triggered_therapeutic_alarm_max', '=', true)
            ->orWhere('triggered_therapeutic_alarm_min', '=', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $days = array();
        $patientsInTable = array();
        
        // for each measurement, get the rest of the patient's measurements in that day
        foreach ($measurementsWithAlarms as $measurementWithAlarm) {
            foreach ($patients as $patient) {
                $datesInTable = array();
                if ($measurementWithAlarm->user->id == $patient->id) {

                    // check if day has been checked
                    $isPatientInChecked = array_key_exists($patient->id, $patientsInTable);
                    $isDateInChecked = false;
                    if ($isPatientInChecked) {
                         $isDateInChecked = in_array($measurementWithAlarm->created_at->copy->startOfDay(), $patientsInTable[$patient->id]);
                    }
                    
                    if (!$isPatientInChecked || ($isPatientInChecked && !$isDateInChecked)) {

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
                // }
                array_push($datesInTable, $measurementInDay);
            }
            $patientsInTable[$patient->id] = Carbon::parse($measurementInDay['created_at'])->copy()->startOfDay();
        }

        //dd($days);
        //dd($days);

        return view('coordinator.dashboard.index', [
            'parameters' => $parameters,
            'alarms' => $days,
        ]);
    } */

    public function index()
    {
        $user = Auth::user();
        $patients = $user->patients;

        $parameters = Parameter::orderBy('id', 'ASC')->get();
        $days = [];

        foreach ($patients as $patient) {
            $measurements = $patient->measurementsAlarms();

            foreach ($measurements as $date => $measurementInDay) {
                $dateInt = Carbon::parse($date)->format('Y-m-d');
                if (!array_key_exists($date, $days)) {
                    $days[$dateInt] = [];
                }

                $days[$dateInt][$patient->id] = ['measurements' => $measurementInDay, 'patient' => $patient, 'date' => $date];
            }
        }

        krsort($days, SORT_STRING);

        return view('coordinator.dashboard.index', [
            'parameters' => $parameters,
            'alarms' => $days,
        ]);
    }
}

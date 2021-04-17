<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use App\Models\Parameter;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
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

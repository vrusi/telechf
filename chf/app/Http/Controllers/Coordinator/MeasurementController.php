<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeasurementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $patient = User::where('id', $request->route('patient'))->first();
        $summary = $patient->measurementsSummary();
        $alarms = $patient->measurementsAlarms();
        $parameters = $patient->parameters;
        $contacts = $patient->contacts;
        return view('coordinator.patients.measurements.index', [
            'patient' => $patient,
            'summary' => $summary,
            'alarms' => $alarms,
            'parameters' => $parameters,
            'contacts' => $contacts,
        ]);
    }

    public function checkDayAlarms(Request $request)
    {
        $patient = User::where('id', $request->route('patient'))->first();
        $date = Carbon::parse($request->date);
        $patient->setMeasurementsInDayChecked($date, true);

        flash('The alarm was successfully checked.')->success();
        return redirect()->action([MeasurementController::class, 'index'], ['patient' => $patient->id]);
    }
}

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

        $checkAll = $request->date == "null";
        if ($checkAll) {
            $success = $patient->setAllMeasurementsChecked(true);

            if ($success) {
                flash('All alarms were successfully checked.')->success();
            } else {
                flash('Something went wrong.')->error();
            }
        } else {
            $date = Carbon::parse($request->date);
            $success = $patient->setMeasurementsInDayChecked($date, true);

            if ($success) {
                flash('The alarm was successfully checked.')->success();
            } else {
                flash('Something went wrong.')->error();
            }
        }

        return redirect()->action([MeasurementController::class, 'index'], ['patient' => $patient->id]);
    }
}

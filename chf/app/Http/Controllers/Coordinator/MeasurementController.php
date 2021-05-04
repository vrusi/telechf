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
        $parameters = $patient->parameters()->orderBy('id', 'ASC')->get();
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
        $locale = $request->getPreferredLanguage(['en', 'sk']);

        $checkAll = $request->date == "null";
        if ($checkAll) {
            $success = $patient->setAllMeasurementsChecked(true);

            if ($success) {
                if ($locale == 'sk') 
                {
                    flash('Všetky alarmy boli označené ako skontrolované.')->success();
                } else {
                    flash('All alarms were successfully checked.')->success();
                }
            } else {
                if ($locale == 'sk') 
                {
                    flash('Alarmy sa nepodarilo označiť ako skontrolované.')->error();
                } else {
                    flash('The alarms could not be checked.')->error();
                }
            }
        } else {
            $date = Carbon::parse($request->date);
            $success = $patient->setMeasurementsInDayChecked($date, true);

            if ($success) {
                if ($locale == 'sk') 
                {
                    flash('Alarm bol označený ako skontrolovaný.')->success();
                } else {
                    flash('The alarm was successfully checked.')->success();
                }
                
            } else {
                if ($locale == 'sk') 
                {
                    flash('Alarm sa nepodarilo označiť ako skontrolovaný.')->error();
                } else {
                    flash('The alarm could not be checked.')->error();
                }
            }
        }

        return redirect()->action([MeasurementController::class, 'index'], ['patient' => $patient->id]);
    }
}

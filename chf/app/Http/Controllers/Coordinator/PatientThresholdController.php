<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Parameter;
use App\Models\User;
use Illuminate\Http\Request;

class PatientThresholdController extends Controller
{
    public function create(Request $request)
    {
        $patient =  User::where('id', $request->route('patient'))->first();
        $parameters = Parameter::orderBy('id', 'ASC')->get();
        return view('coordinator.patients.therapy.thresholds.create', ['patient' => $patient, 'parameters' => $parameters]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parameter1minSafety' => 'nullable|numeric|min:1',
            'parameter1maxSafety' => 'nullable|numeric|min:1',
            'parameter1minTherapeutic' => 'nullable|numeric|min:1',
            'parameter1maxTherapeutic' => 'nullable|numeric|min:1',
            'parameter1times' => 'nullable|numeric|min:1',
            'parameter1per' => 'nullable|in:hour,day,week,month',

            'parameter2minSafety' => 'nullable|numeric|min:1',
            'parameter2maxSafety' => 'nullable|numeric|min:1',
            'parameter2minTherapeutic' => 'nullable|numeric|min:1',
            'parameter2maxTherapeutic' => 'nullable|numeric|min:1',
            'parameter2times' => 'nullable|numeric|min:1',
            'parameter2per' => 'nullable|in:hour,day,week,month',

            'parameter3minSafety' => 'nullable|numeric|min:1',
            'parameter3maxSafety' => 'nullable|numeric|min:1',
            'parameter3minTherapeutic' => 'nullable|numeric|min:1',
            'parameter3maxTherapeutic' => 'nullable|numeric|min:1',
            'parameter3times' => 'nullable|numeric|min:1',
            'parameter3per' => 'nullable|in:hour,day,week,month',

            'parameter4minSafety' => 'nullable|numeric|min:1',
            'parameter4maxSafety' => 'nullable|numeric|min:1',
            'parameter4minTherapeutic' => 'nullable|numeric|min:1',
            'parameter4maxTherapeutic' => 'nullable|numeric|min:1',
            'parameter4times' => 'nullable|numeric|min:1',
            'parameter4per' => 'nullable|in:hour,day,week,month',

            'parameter5minSafety' => 'nullable|numeric|min:1',
            'parameter5maxSafety' => 'nullable|numeric|min:1',
            'parameter5minTherapeutic' => 'nullable|numeric|min:1',
            'parameter5maxTherapeutic' => 'nullable|numeric|min:1',
            'parameter5times' => 'nullable|numeric|min:1',
            'parameter5per' => 'nullable|in:hour,day,week,month',

            'parameter6minSafety' => 'nullable|numeric|min:1',
            'parameter6maxSafety' => 'nullable|numeric|min:1',
            'parameter6minTherapeutic' => 'nullable|numeric|min:1',
            'parameter6maxTherapeutic' => 'nullable|numeric|min:1',
            'parameter6times' => 'nullable|numeric|min:1',
            'parameter6per' => 'nullable|in:hour,day,week,month',

            'parameter7minSafety' => 'nullable|numeric|min:1',
            'parameter7maxSafety' => 'nullable|numeric|min:1',
            'parameter7minTherapeutic' => 'nullable|numeric|min:1',
            'parameter7maxTherapeutic' => 'nullable|numeric|min:1',
            'parameter7times' => 'nullable|numeric|min:1',
            'parameter7per' => 'nullable|in:hour,day,week,month',

            'patientId' => 'required|exists:users,id',
        ]);

        $patient = User::where('id', $validated['patientId'])->first();
        //$patientParameters = $patient->parameters()->orderBy('id', 'ASC')->get();
        $patientParameters = Parameter::orderBy('id', 'ASC')->get();

        $parameter1 =  Parameter::where('id', 1)->first();
        if (!$patient->hasParameter($parameter1)) {
            $patient->parameters()->attach($parameter1);
        }

        $patient->parameters()->updateExistingPivot(1, [
            'threshold_safety_min' => $request->parameter1minSafetyCheck ? null : ($validated['parameter1minSafety'] ?? ($patientParameters[0]->pivot->threshold_safety_min ?? null)),
            'threshold_safety_max' => $request->parameter1minSafetyCheck ? null : ($validated['parameter1maxSafety'] ?? ($patientParameters[0]->pivot->threshold_safety_max ?? null)),

            'threshold_therapeutic_min' => $request->parameter1maxTherapeuticCheck ? null : ($validated['parameter1minTherapeutic'] ?? ($patientParameters[0]->pivot->threshold_therapeutic_min ?? null )),
            'threshold_therapeutic_max' => $request->parameter1maxTherapeuticCheck ? null : ($validated['parameter1maxTherapeutic'] ?? ($patientParameters[0]->pivot->threshold_therapeutic_max ?? null )),

            'measurement_times' => $request->parameter1freqCheck ? null : ($validated['parameter1times'] ?? ($patientParameters[0]->pivot->measurement_times ?? null )),
            'measurement_span' => $request->parameter1freqCheck ? null : ($validated['parameter1per'] ?? ($patientParameters[0]->pivot->measurement_span ?? null )),
        ]);


        $parameter2 =  Parameter::where('id', 2)->first();
        if (!$patient->hasParameter($parameter2)) {
            $patient->parameters()->attach($parameter2);
        }

        $patient->parameters()->updateExistingPivot(2, [
            'threshold_safety_min' => $request->parameter2minSafetyCheck ? null : ($validated['parameter2minSafety'] ?? ($patientParameters[1]->pivot->threshold_safety_min ?? null )),
            'threshold_safety_max' => $request->parameter2minSafetyCheck ? null : ($validated['parameter2maxSafety'] ?? ($patientParameters[1]->pivot->threshold_safety_max ?? null )),

            'threshold_therapeutic_min' => $request->parameter2maxTherapeuticCheck ? null : ($validated['parameter2minTherapeutic'] ?? ($patientParameters[1]->pivot->threshold_therapeutic_min ?? null )),
            'threshold_therapeutic_max' => $request->parameter2maxTherapeuticCheck ? null : ($validated['parameter2maxTherapeutic'] ?? ($patientParameters[1]->pivot->threshold_therapeutic_max ?? null )),

            'measurement_times' => $request->parameter2freqCheck ? null : ($validated['parameter2times'] ?? ($patientParameters[1]->pivot->measurement_times ?? null )),
            'measurement_span' => $request->parameter2freqCheck ? null : ($validated['parameter2per'] ?? ($patientParameters[1]->pivot->measurement_span ?? null )),
        ]);


        $parameter3 =  Parameter::where('id', 3)->first();
        if (!$patient->hasParameter($parameter3)) {
            $patient->parameters()->attach($parameter3);
        }

        $patient->parameters()->updateExistingPivot(3, [
            'threshold_safety_min' => $request->parameter3minSafetyCheck ? null : ($validated['parameter3minSafety'] ?? ($patientParameters[2]->pivot->threshold_safety_min ?? null )),
            'threshold_safety_max' => $request->parameter3minSafetyCheck ? null : ($validated['parameter3maxSafety'] ?? ($patientParameters[2]->pivot->threshold_safety_max ?? null )),

            'threshold_therapeutic_min' => $request->parameter3maxTherapeuticCheck ? null : ($validated['parameter3minTherapeutic'] ?? ($patientParameters[2]->pivot->threshold_therapeutic_min ?? null )),
            'threshold_therapeutic_max' => $request->parameter3maxTherapeuticCheck ? null : ($validated['parameter3maxTherapeutic'] ?? ($patientParameters[2]->pivot->threshold_therapeutic_max ?? null )),

            'measurement_times' => $request->parameter3freqCheck ? null : ($validated['parameter3times'] ?? ($patientParameters[2]->pivot->measurement_times ?? null )),
            'measurement_span' => $request->parameter3freqCheck ? null : ($validated['parameter3per'] ?? ($patientParameters[2]->pivot->measurement_span ?? null )),
        ]);


        $parameter4 =  Parameter::where('id', 4)->first();
        if (!$patient->hasParameter($parameter4)) {
            $patient->parameters()->attach($parameter4);
        }

        $patient->parameters()->updateExistingPivot(4, [
            'threshold_safety_min' => $request->parameter4minSafetyCheck ? null : ($validated['parameter4minSafety'] ?? ($patientParameters[3]->pivot->threshold_safety_min ?? null )),
            'threshold_safety_max' => $request->parameter4minSafetyCheck ? null : ($validated['parameter4maxSafety'] ?? ($patientParameters[3]->pivot->threshold_safety_max ?? null )),

            'threshold_therapeutic_min' => $request->parameter4maxTherapeuticCheck ? null : ($validated['parameter4minTherapeutic'] ?? ($patientParameters[3]->pivot->threshold_therapeutic_min ?? null )),
            'threshold_therapeutic_max' => $request->parameter4maxTherapeuticCheck ? null : ($validated['parameter4maxTherapeutic'] ?? ($patientParameters[3]->pivot->threshold_therapeutic_max ?? null )),

            'measurement_times' => $request->parameter4freqCheck ? null : ($validated['parameter4times'] ?? ($patientParameters[3]->pivot->measurement_times ?? null )),
            'measurement_span' => $request->parameter4freqCheck ? null : ($validated['parameter4per'] ?? ($patientParameters[3]->pivot->measurement_span ?? null )),
        ]);


        $parameter5 =  Parameter::where('id', 5)->first();
        if ($patient->hasParameter($parameter5)) {

            $patient->parameters()->updateExistingPivot(5, [
                'threshold_safety_min' => $request->parameter5minSafetyCheck ? null : ($validated['parameter5minSafety'] ?? ($patientParameters[4]->pivot->threshold_safety_min ?? null )),
                'threshold_safety_max' => $request->parameter5minSafetyCheck ? null : ($validated['parameter5maxSafety'] ?? ($patientParameters[4]->pivot->threshold_safety_max ?? null )),

                'threshold_therapeutic_min' => $request->parameter5maxTherapeuticCheck ? null : ($validated['parameter5minTherapeutic'] ?? ($patientParameters[4]->pivot->threshold_therapeutic_min ?? null )),
                'threshold_therapeutic_max' => $request->parameter5maxTherapeuticCheck ? null : ($validated['parameter5maxTherapeutic'] ?? ($patientParameters[4]->pivot->threshold_therapeutic_max ?? null )),

                'measurement_times' => $request->parameter5freqCheck ? null : ($validated['parameter5times'] ?? ($patientParameters[4]->pivot->measurement_times ?? null )),
                'measurement_span' => $request->parameter5freqCheck ? null : ($validated['parameter5per'] ?? ($patientParameters[4]->pivot->measurement_span ?? null )),
            ]);
        }

        $parameter6 =  Parameter::where('id', 6)->first();
        if (!$patient->hasParameter($parameter6)) {
            $patient->parameters()->attach($parameter6);
        }

        $patient->parameters()->updateExistingPivot(6, [
            'threshold_safety_min' => $request->parameter6minSafetyCheck ? null : ($validated['parameter6minSafety'] ?? ($patientParameters[5]->pivot->threshold_safety_min ?? null )),
            'threshold_safety_max' => $request->parameter6minSafetyCheck ? null : ($validated['parameter6maxSafety'] ?? ($patientParameters[5]->pivot->threshold_safety_max ?? null )),

            'threshold_therapeutic_min' => $request->parameter6maxTherapeuticCheck ? null : ($validated['parameter6minTherapeutic'] ?? ($patientParameters[5]->pivot->threshold_therapeutic_min ?? null )),
            'threshold_therapeutic_max' => $request->parameter6maxTherapeuticCheck ? null : ($validated['parameter6maxTherapeutic'] ?? ($patientParameters[5]->pivot->threshold_therapeutic_max ?? null )),

            'measurement_times' => $request->parameter6freqCheck ? null : ($validated['parameter6times'] ?? ($patientParameters[5]->pivot->measurement_times ?? null )),
            'measurement_span' => $request->parameter6freqCheck ? null : ($validated['parameter6per'] ?? ($patientParameters[5]->pivot->measurement_span ?? null )),
        ]);


        $parameter7 =  Parameter::where('id', 7)->first();
        if (!$patient->hasParameter($parameter7)) {
            $patient->parameters()->attach($parameter7);
        }

        $patient->parameters()->updateExistingPivot(7, [
            'threshold_safety_min' => $request->parameter7minSafetyCheck ? null : ($validated['parameter7minSafety'] ?? ($patientParameters[6]->pivot->threshold_safety_min ?? null )),
            'threshold_safety_max' => $request->parameter7minSafetyCheck ? null : ($validated['parameter7maxSafety'] ?? ($patientParameters[6]->pivot->threshold_safety_max ?? null )),

            'threshold_therapeutic_min' => $request->parameter7maxTherapeuticCheck ? null : ($validated['parameter7minTherapeutic'] ?? ($patientParameters[6]->pivot->threshold_therapeutic_min ?? null )),
            'threshold_therapeutic_max' => $request->parameter7maxTherapeuticCheck ? null : ($validated['parameter7maxTherapeutic'] ?? ($patientParameters[6]->pivot->threshold_therapeutic_max ?? null )),

            'measurement_times' => $request->parameter7freqCheck ? null : ($validated['parameter7times'] ?? ($patientParameters[6]->pivot->measurement_times ?? null )),
            'measurement_span' => $request->parameter7freqCheck ? null : ($validated['parameter7per'] ?? ($patientParameters[6]->pivot->measurement_span ?? null )),
        ]);

        $locale = $request->getPreferredLanguage(['en', 'sk']);
        if ($locale == 'sk') {
            flash('Limity boli úspešne upravené.')->success();
        } else {
            flash('The thresholds have been updated.')->success();
        }

        return redirect()->action([ProfileController::class, 'therapy'], ['patient' => $patient->id]);
    }
}

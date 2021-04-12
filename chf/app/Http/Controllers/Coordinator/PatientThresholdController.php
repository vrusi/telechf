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
        $parameters = $patient->parameters()->orderBy('id', 'ASC')->get();
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

        $patient = User::where('id', $request->patientId)->first();
        $patientParameters = $patient->parameters()->orderBy('id', 'ASC')->get();

        $parameter1 =  Parameter::where('id', 1)->first();
        if ($patient->hasParameter($parameter1)) {
            
            $patient->parameters()->updateExistingPivot(1, [
                'threshold_safety_min' => $request->parameter1minSafetyCheck ? null : ( $request->parameter1minSafety ?? $patientParameters[0]->pivot->threshold_safety_min ),
                'threshold_safety_max' => $request->parameter1minSafetyCheck ? null : ( $request->parameter1maxSafety ?? $patientParameters[0]->pivot->threshold_safety_max ),

                'threshold_therapeutic_min' => $request->parameter1maxTherapeuticCheck ? null : ( $request->parameter1minTherapeutic ?? $patientParameters[0]->pivot->threshold_therapeutic_min ),
                'threshold_therapeutic_max' => $request->parameter1maxTherapeuticCheck ? null : ( $request->parameter1maxTherapeutic ?? $patientParameters[0]->pivot->threshold_therapeutic_max ),

                'measurement_times' => $request->parameter1freqCheck ? null : ($request->parameter1times ?? $patientParameters[0]->pivot->measurement_times),
                'measurement_span' => $request->parameter1freqCheck ? null : ($request->parameter1per ?? $patientParameters[0]->pivot->measurement_span),
            ]);
        }

        $parameter2 =  Parameter::where('id', 2)->first();
        if ($patient->hasParameter($parameter2)) {
            
            $patient->parameters()->updateExistingPivot(2, [
                'threshold_safety_min' => $request->parameter2minSafetyCheck ? null : ( $request->parameter2minSafety ?? $patientParameters[1]->pivot->threshold_safety_min ),
                'threshold_safety_max' => $request->parameter2minSafetyCheck ? null : ( $request->parameter2maxSafety ?? $patientParameters[1]->pivot->threshold_safety_max ),

                'threshold_therapeutic_min' => $request->parameter2maxTherapeuticCheck ? null : ( $request->parameter2minTherapeutic ?? $patientParameters[1]->pivot->threshold_therapeutic_min ),
                'threshold_therapeutic_max' => $request->parameter2maxTherapeuticCheck ? null : ( $request->parameter2maxTherapeutic ?? $patientParameters[1]->pivot->threshold_therapeutic_max ),

                'measurement_times' => $request->parameter2freqCheck ? null : ($request->parameter2times ?? $patientParameters[1]->pivot->measurement_times),
                'measurement_span' => $request->parameter2freqCheck ? null : ($request->parameter2per ?? $patientParameters[1]->pivot->measurement_span),
            ]);
        }

        $parameter3 =  Parameter::where('id', 3)->first();
        if ($patient->hasParameter($parameter3)) {
            
            $patient->parameters()->updateExistingPivot(3, [
                'threshold_safety_min' => $request->parameter3minSafetyCheck ? null : ( $request->parameter3minSafety ?? $patientParameters[2]->pivot->threshold_safety_min ),
                'threshold_safety_max' => $request->parameter3minSafetyCheck ? null : ( $request->parameter3maxSafety ?? $patientParameters[2]->pivot->threshold_safety_max ),

                'threshold_therapeutic_min' => $request->parameter3maxTherapeuticCheck ? null : ( $request->parameter3minTherapeutic ?? $patientParameters[2]->pivot->threshold_therapeutic_min ),
                'threshold_therapeutic_max' => $request->parameter3maxTherapeuticCheck ? null : ( $request->parameter3maxTherapeutic ?? $patientParameters[2]->pivot->threshold_therapeutic_max ),

                'measurement_times' => $request->parameter3freqCheck ? null : ($request->parameter3times ?? $patientParameters[2]->pivot->measurement_times),
                'measurement_span' => $request->parameter3freqCheck ? null : ($request->parameter3per ?? $patientParameters[2]->pivot->measurement_span),
            ]);
        }

        $parameter4 =  Parameter::where('id', 4)->first();
        if ($patient->hasParameter($parameter4)) {
            
            $patient->parameters()->updateExistingPivot(4, [
                'threshold_safety_min' => $request->parameter4minSafetyCheck ? null : ( $request->parameter4minSafety ?? $patientParameters[3]->pivot->threshold_safety_min ),
                'threshold_safety_max' => $request->parameter4minSafetyCheck ? null : ( $request->parameter4maxSafety ?? $patientParameters[3]->pivot->threshold_safety_max ),

                'threshold_therapeutic_min' => $request->parameter4maxTherapeuticCheck ? null : ( $request->parameter4minTherapeutic ?? $patientParameters[3]->pivot->threshold_therapeutic_min ),
                'threshold_therapeutic_max' => $request->parameter4maxTherapeuticCheck ? null : ( $request->parameter4maxTherapeutic ?? $patientParameters[3]->pivot->threshold_therapeutic_max ),

                'measurement_times' => $request->parameter4freqCheck ? null : ($request->parameter4times ?? $patientParameters[3]->pivot->measurement_times),
                'measurement_span' => $request->parameter4freqCheck ? null : ($request->parameter4per ?? $patientParameters[3]->pivot->measurement_span),
            ]);
        }

        $parameter5 =  Parameter::where('id', 5)->first();
        if ($patient->hasParameter($parameter5)) {
            
            $patient->parameters()->updateExistingPivot(5, [
                'threshold_safety_min' => $request->parameter5minSafetyCheck ? null : ( $request->parameter5minSafety ?? $patientParameters[4]->pivot->threshold_safety_min ),
                'threshold_safety_max' => $request->parameter5minSafetyCheck ? null : ( $request->parameter5maxSafety ?? $patientParameters[4]->pivot->threshold_safety_max ),

                'threshold_therapeutic_min' => $request->parameter5maxTherapeuticCheck ? null : ( $request->parameter5minTherapeutic ?? $patientParameters[4]->pivot->threshold_therapeutic_min ),
                'threshold_therapeutic_max' => $request->parameter5maxTherapeuticCheck ? null : ( $request->parameter5maxTherapeutic ?? $patientParameters[4]->pivot->threshold_therapeutic_max ),

                'measurement_times' => $request->parameter5freqCheck ? null : ($request->parameter5times ?? $patientParameters[4]->pivot->measurement_times),
                'measurement_span' => $request->parameter5freqCheck ? null : ($request->parameter5per ?? $patientParameters[4]->pivot->measurement_span),
            ]);
        }

        $parameter6 =  Parameter::where('id', 6)->first();
        if ($patient->hasParameter($parameter6)) {
            
            $patient->parameters()->updateExistingPivot(6, [
                'threshold_safety_min' => $request->parameter6minSafetyCheck ? null : ( $request->parameter6minSafety ?? $patientParameters[5]->pivot->threshold_safety_min ),
                'threshold_safety_max' => $request->parameter6minSafetyCheck ? null : ( $request->parameter6maxSafety ?? $patientParameters[5]->pivot->threshold_safety_max ),

                'threshold_therapeutic_min' => $request->parameter6maxTherapeuticCheck ? null : ( $request->parameter6minTherapeutic ?? $patientParameters[5]->pivot->threshold_therapeutic_min ),
                'threshold_therapeutic_max' => $request->parameter6maxTherapeuticCheck ? null : ( $request->parameter6maxTherapeutic ?? $patientParameters[5]->pivot->threshold_therapeutic_max ),

                'measurement_times' => $request->parameter6freqCheck ? null : ($request->parameter6times ?? $patientParameters[5]->pivot->measurement_times),
                'measurement_span' => $request->parameter6freqCheck ? null : ($request->parameter6per ?? $patientParameters[5]->pivot->measurement_span),
            ]);
        }

        $parameter7 =  Parameter::where('id', 7)->first();
        if ($patient->hasParameter($parameter7)) {
            
            $patient->parameters()->updateExistingPivot(7, [
                'threshold_safety_min' => $request->parameter7minSafetyCheck ? null : ( $request->parameter7minSafety ?? $patientParameters[6]->pivot->threshold_safety_min ),
                'threshold_safety_max' => $request->parameter7minSafetyCheck ? null : ( $request->parameter7maxSafety ?? $patientParameters[6]->pivot->threshold_safety_max ),

                'threshold_therapeutic_min' => $request->parameter7maxTherapeuticCheck ? null : ( $request->parameter7minTherapeutic ?? $patientParameters[6]->pivot->threshold_therapeutic_min ),
                'threshold_therapeutic_max' => $request->parameter7maxTherapeuticCheck ? null : ( $request->parameter7maxTherapeutic ?? $patientParameters[6]->pivot->threshold_therapeutic_max ),

                'measurement_times' => $request->parameter7freqCheck ? null : ($request->parameter7times ?? $patientParameters[6]->pivot->measurement_times),
                'measurement_span' => $request->parameter7freqCheck ? null : ($request->parameter7per ?? $patientParameters[6]->pivot->measurement_span),
            ]);
        }


        flash('The thresholds have been updated.')->success();
        return redirect()->action([ProfileController::class, 'therapy'], ['patient' => $patient->id]);
    }
}

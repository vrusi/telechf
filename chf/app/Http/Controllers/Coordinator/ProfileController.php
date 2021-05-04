<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use DateTime;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $patient = User::where('id', $request->route('patient'))->first();
        return view('coordinator.patients.profile.index', ['patient' => $patient]);
    }

    public function create(Request $request)
    {
        $patient = User::where('id', $request->route('patient'))->first();
        return view('coordinator.patients.profile.create', ['patient' => $patient]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'patientId' => 'nullable|exists:users,id',
            'name' => 'nullable',
            'surname' => 'nullable',
            'sex' => 'nullable|in:male,female',
            'birthDay' => 'nullable|integer|between:1,31',
            'birthMonth' => 'nullable|integer|between:1,12',
            'birthYear' => 'nullable|integer|between:1900,2021',
            'height' => 'nullable|min:1',
            'weight' => 'nullable|min:1',
            'email' => 'nullable',
            'mobile' => 'nullable',
            'password' => 'nullable',
            'externalDoctorId' => 'nullable',
            'externalId' => 'nullable',
            'mac' => ['nullable', 'string', 'regex:/^([0-9A-Fa-f]{2}[:]){5}([0-9A-Fa-f]{2})$/'],
        ]);

        if ($request['birthDay'] && $request['birthMonth'] && $request['birthYear']) {

            // validate date of birth
            if (in_array($validated['birthMonth'], [4, 6, 9, 11])) {
                if ($validated['birthDay'] >= 31) {
                    $dateObj = DateTime::createFromFormat('!m', $validated['birthMonth']);
                    $monthName = $dateObj->format('F');
                    throw ValidationException::withMessages([$monthName . ' has only 30 days.']);
                }
            }

            if ($validated['birthMonth'] == 2) {
                // leap year
                if ($validated['birthYear'] % 4 != 0) {
                    if ($validated['birthDay'] >= 29) {
                        throw ValidationException::withMessages(['February ' . strval($validated['birthYear']) . ' has only 28 days.']);
                    }
                    // non-leap year
                } else {
                    if ($validated['birthDay'] >= 30) {
                        throw ValidationException::withMessages(['February ' . strval($validated['birthYear']) . ' has only 29 days.']);
                    }
                }
            }
        }

        if ($validated['mac'] && $validated['externalId']) {
            $responseMac = Http::post(
                'http://147.175.106.7:5000/api/patient/addSensor',
                [
                    'MAC' => $validated['mac'],
                    'patientId' => $validated['externalId'],
                ]
            );
        }

        $dateOfBirth = $request['birthDay'] && $request['birthMonth'] && $request['birthYear'] ? strval($validated['birthYear']) . '-' . strval($validated['birthMonth']) . '-' . strval($validated['birthDay']) : null;
        $patient = User::where('id', $validated['patientId'])->update([
            'id_external' => $validated['externalId'],
            'name' => $validated['name'] ?? null,
            'surname' =>  $validated['surname'] ?? null,
            'sex' => $validated['sex'] ?? null,
            'dob' =>  $dateOfBirth ?? null,
            'height' =>  $validated['height'] ?? null,
            'weight' =>  $validated['weight'] ?? null,
            'email' =>  $validated['email'],
            'mobile' =>  $validated['mobile'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_coordinator' => false,
            'mac' => $validated['mac'] ?? null,
            'id_external_doctor' => $validated['externalDoctorId'],
        ]);

        return redirect()->action([ProfileController::class, 'index'], ['patient' => $validated['patientId']]);
    }


    public function therapy(Request $request)
    {
        $user = Auth::user();
        $patient = User::where('id', $request->route('patient'))->first();
        $thresholds = $patient->thresholds();
        $parameters = $patient->parameters()->orderBy('id', 'ASC')->get();
        $conditions = $patient->conditions;
        $drugs = $patient->drugs;
        $locale = $request->getPreferredLanguage(['en', 'sk']);
        return view('coordinator.patients.therapy.index', [
            'patient' => $patient,
            'parameters' => $parameters,
            'conditions' => $conditions,
            'drugs' => $drugs,
            'thresholds' => $thresholds,
            'locale' => $locale,
        ]);
    }
}

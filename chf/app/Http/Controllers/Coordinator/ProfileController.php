<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Condition;
use App\Models\Drug;
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
        if (!$patient) {
            $locale = $request->getPreferredLanguage(['en', 'sk']);
            if ($locale == 'sk') {
                flash('Používateľ s požadovaným ID sa nenašiel.')->error();
            } else {
                flash('No user with the specified ID was found.')->error();
            }
            return redirect()->action([PatientController::class, 'index']);
        }
        return view('coordinator.patients.profile.index', ['patient' => $patient]);
    }

    public function create(Request $request)
    {
        $patient = User::where('id', $request->route('patient'))->first();
        if (!$patient) {
            $locale = $request->getPreferredLanguage(['en', 'sk']);
            if ($locale == 'sk') {
                flash('Používateľ s požadovaným ID sa nenašiel.')->error();
            } else {
                flash('No user with the specified ID was found.')->error();
            }
            return redirect()->action([PatientController::class, 'index']);
        }
        return view('coordinator.patients.profile.create', ['patient' => $patient]);
    }

    public function createRecommendations(Request $request)
    {
        $patient = User::where('id', $request->route('patient'))->first();
        if (!$patient) {
            $locale = $request->getPreferredLanguage(['en', 'sk']);
            if ($locale == 'sk') {
                flash('Používateľ s požadovaným ID sa nenašiel.')->error();
            } else {
                flash('No user with the specified ID was found.')->error();
            }
            return redirect()->action([PatientController::class, 'index']);
        }
        return view('coordinator.patients.profile.recommendations.create', ['patient' => $patient]);
    }

    public function storeRecommendations(Request $request)
    {
        $patient = User::where('id', $request->route('patient'))->first();
        if (!$patient) {
            $locale = $request->getPreferredLanguage(['en', 'sk']);
            if ($locale == 'sk') {
                flash('Používateľ s požadovaným ID sa nenašiel.')->error();
            } else {
                flash('No user with the specified ID was found.')->error();
            }
            return redirect()->action([PatientController::class, 'index']);
        }
        $patient->recommendations = $request->recommendations;
        $success = $patient->save();
        $locale = $request->getPreferredLanguage(['en', 'sk']);

        if ($locale == 'sk') {
            flash('Odporúčania pre pacienta boli uložené.')->success();
        } else {
            flash('The recommendations for the patient were saved.')->success();
        }

        return redirect()->action([ProfileController::class, 'therapy'], ['patient' => $patient]);
    }

    public function createConditions(Request $request)
    {
        $patient = User::where('id', $request->route('patient'))->first();
        if (!$patient) {
            $locale = $request->getPreferredLanguage(['en', 'sk']);
            if ($locale == 'sk') {
                flash('Používateľ s požadovaným ID sa nenašiel.')->error();
            } else {
                flash('No user with the specified ID was found.')->error();
            }
            return redirect()->action([PatientController::class, 'index']);
        }
        $conditions = Condition::orderBy('name', 'ASC')->get();
        return view('coordinator.patients.profile.conditions.create', [
            'patient' => $patient,
            'conditions' => $conditions
        ]);
    }

    public function storeConditions(Request $request)
    {
        $patient = User::where('id', $request->route('patient'))->first();
        if (!$patient) {
            $locale = $request->getPreferredLanguage(['en', 'sk']);
            if ($locale == 'sk') {
                flash('Používateľ s požadovaným ID sa nenašiel.')->error();
            } else {
                flash('No user with the specified ID was found.')->error();
            }
            return redirect()->action([PatientController::class, 'index']);
        }
        $locale = $request->getPreferredLanguage(['en', 'sk']);

        if ($request->purge == "on") {
            // remove all conditions
            $patient->purgeConditions();
            if ($locale == 'sk') {
                flash('Všetky pacientove stavy boli zmazané.')->success();
            } else {
                flash('All the conditions were removed.')->success();
            }
        } else {

            if ($request->conditions) {
                // first remove all
                $patient->purgeConditions();

                // then attach the specified conditions
                foreach ($request->conditions as $conditionId) {
                    $patient->conditions()->attach($conditionId);
                }

                $success = $patient->save();

                if ($locale == 'sk') {
                    flash('Stav pre pacienta bol uložený.')->success();
                } else {
                    flash('The conditions for the patient were saved.')->success();
                }
            }
        }

        return redirect()->action([ProfileController::class, 'therapy'], ['patient' => $patient]);
    }

    public function createPrescriptions(Request $request)
    {
        $patient = User::where('id', $request->route('patient'))->first();
        if (!$patient) {
            $locale = $request->getPreferredLanguage(['en', 'sk']);
            if ($locale == 'sk') {
                flash('Používateľ s požadovaným ID sa nenašiel.')->error();
            } else {
                flash('No user with the specified ID was found.')->error();
            }
            return redirect()->action([PatientController::class, 'index']);
        }
        $drugs = Drug::all();
        $drugsPatient = $patient->drugs;
        return view('coordinator.patients.profile.prescriptions.create', [
            'patient' => $patient,
            'drugs' => $drugs,
            'drugsPatient' => $drugsPatient,
        ]);
    }

    public function storePrescriptions(Request $request)
    {
        $patient = User::where('id', $request->route('patient'))->first();
        if (!$patient) {
            $locale = $request->getPreferredLanguage(['en', 'sk']);
            if ($locale == 'sk') {
                flash('Používateľ s požadovaným ID sa nenašiel.')->error();
            } else {
                flash('No user with the specified ID was found.')->error();
            }
            return redirect()->action([PatientController::class, 'index']);
        }
        $validated = $request->validate([
            'drug1volume' => 'nullable|numeric',
            'drug1unit' => 'nullable',
            'drug1times' => 'nullable|numeric|min:1',
            'drug1per' => 'nullable|in:hour,day,week,month',

            'drug2volume' => 'nullable|numeric',
            'drug2unit' => 'nullable',
            'drug2times' => 'nullable|numeric|min:1',
            'drug2per' => 'nullable|in:hour,day,week,month',

            'drug3volume' => 'nullable|numeric',
            'drug3unit' => 'nullable',
            'drug3times' => 'nullable|numeric|min:1',
            'drug3per' => 'nullable|in:hour,day,week,month',

            'drug4times' => 'nullable|numeric|min:1',
            'drug4per' => 'nullable|in:hour,day,week,month',
            'drug4times' => 'nullable|numeric|min:1',
            'drug4per' => 'nullable|in:hour,day,week,month',
        ]);

        $patient->purgeDrugs();
        $attach1 = null;
        $attach2 = null;
        $attach3 = null;
        $update1 = null;
        $update2 = null;
        $update3 = null;

        if ($request->drug1) {
            $drug = Drug::where('id', 1)->first();
            $attach1 = $patient->drugs()->attach(1);
            $update1 = $patient->drugs()->updateExistingPivot(
                1,
                [
                    'dosage_volume' => $validated['drug1volume'],
                    'dosage_unit' => $validated['drug1unit'],
                    'dosage_times' => $validated['drug1times'],
                    'dosage_span' => $validated['drug1per'],
                ]
            );
        }

        if ($request->drug2) {
            $drug = Drug::where('id', 2)->first();
            $attach2 = $patient->drugs()->attach(2);
            $update2 = $patient->drugs()->updateExistingPivot(
                2,
                [
                    'dosage_volume' => $validated['drug2volume'],
                    'dosage_unit' => $validated['drug2unit'],
                    'dosage_times' => $validated['drug2times'],
                    'dosage_span' => $validated['drug2per'],
                ]
            );
        }

        if ($request->drug3) {
            $drug = Drug::where('id', 3)->first();
            $attach3 =  $patient->drugs()->attach(3);
            $update3 =  $patient->drugs()->updateExistingPivot(
                3,
                [
                    'dosage_volume' => $validated['drug3volume'],
                    'dosage_unit' => $validated['drug3unit'],
                    'dosage_times' => $validated['drug3times'],
                    'dosage_span' => $validated['drug3per'],
                ]
            );
        }

        $success = $patient->save();

        return redirect()->action([ProfileController::class, 'therapy'], ['patient' => $patient]);
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
        $result = User::where('id', $validated['patientId'])->update([
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

        $locale = $request->getPreferredLanguage(['en', 'sk']);

        if ($result == 0) {
            if ($locale == 'sk') {
                flash('Pacientove údaje neboli zmenené.')->success();
            } else {
                flash('The patient\'s personal information was not updated.')->success();
            }
        }
        if ($result == 1) {
            if ($locale == 'sk') {
                flash('Pacientove údaje boli úspešne zmenené.')->success();
            } else {
                flash('The patient\'s personal information successfully updated.')->success();
            }
        }

        return redirect()->action([ProfileController::class, 'index'], ['patient' => $validated['patientId']]);
    }


    public function therapy(Request $request)
    {
        $patient = User::where('id', $request->route('patient'))->first();
        if (!$patient) {
            $locale = $request->getPreferredLanguage(['en', 'sk']);
            if ($locale == 'sk') {
                flash('Používateľ s požadovaným ID sa nenašiel.')->error();
            } else {
                flash('No user with the specified ID was found.')->error();
            }
            return redirect()->action([PatientController::class, 'index']);
        }
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

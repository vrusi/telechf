<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Condition;
use App\Models\Drug;
use App\Models\ECG;
use App\Models\Parameter;
use App\Models\User;
use App\Utils\Parser;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $inactive = $request->query('inactive');
        $coordinator = Auth::user();

        $patients = null;
        if ($inactive) {
            $patients = User::onlyTrashed()->where('coordinator_id', $coordinator->id)->get();
        } else {
            $patients = $coordinator->patients;
        }

        return view('coordinator.patients.index', [
            'patients' => $patients,
            'active' => !$inactive,
        ]);
    }

    public function show(Request $request)
    {
        $patient =  User::where('id', $request->route('patient'))->first();
        if (!$patient) {
            $locale = $request->getPreferredLanguage(['en', 'sk']);
            if ($locale == 'sk') {
                flash('Používateľ s požadovaným ID sa nenašiel.')->error();
            } else {
                flash('No user with the specified ID was found.')->error();
            }
            return redirect()->action([PatientController::class, 'index']);
        }
        return redirect()->action([ProfileController::class, 'index'], ['patient' => $patient->id]);
    }

    public function create(Request $request)
    {
        $parameters = Parameter::orderBy('id', 'ASC')->get();
        $conditions = Condition::orderBy('name', 'ASC')->get();
        $drugs = Drug::orderBy('name', 'ASC')->get();
        $coordinator = Auth::user();
        return view('coordinator.patients.create', [
            'parameters' => $parameters,
            'conditions' => $conditions,
            'drugs' => $drugs,
            'coordinator' => $coordinator,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable',
            'surname' => 'nullable',
            'sex' => 'nullable|in:male,female',

            'birthDay' => 'nullable|integer|between:1,31',
            'birthMonth' => 'nullable|integer|between:1,12',
            'birthYear' => 'nullable|integer|between:1900,2021',

            'height' => 'nullable|min:1',
            'weight' => 'nullable|min:1',
            'email' => 'required',
            'mobile' => 'nullable',
            'coordinatorId' => 'nullable|exists:users,id',
            'password' => 'required',
            'mac' => ['nullable', 'string', 'regex:/^([0-9A-Fa-f]{2}[:]){5}([0-9A-Fa-f]{2})$/'],

            'parameter1MinSafety' => 'nullable|numeric|min:1',
            'parameter1MaxSafety' => 'nullable|numeric|min:1',
            'parameter1MinTherapeutic' => 'nullable|numeric|min:1',
            'parameter1MaxTherapeutic' => 'nullable|numeric|min:1',

            'parameter2MinSafety' => 'nullable|numeric|min:1',
            'parameter2MaxSafety' => 'nullable|numeric|min:1',
            'parameter2MinTherapeutic' => 'nullable|numeric|min:1',
            'parameter2MaxTherapeutic' => 'nullable|numeric|min:1',

            'parameter3MinSafety' => 'nullable|numeric|min:1',
            'parameter3MaxSafety' => 'nullable|numeric|min:1',
            'parameter3MinTherapeutic' => 'nullable|numeric|min:1',
            'parameter3MaxTherapeutic' => 'nullable|numeric|min:1',

            'parameter4MinSafety' => 'nullable|numeric|min:1',
            'parameter4MaxSafety' => 'nullable|numeric|min:1',
            'parameter4MinTherapeutic' => 'nullable|numeric|min:1',
            'parameter4MaxTherapeutic' => 'nullable|numeric|min:1',

            'parameter5MinSafety' => 'nullable|numeric|min:1',
            'parameter5MaxSafety' => 'nullable|numeric|min:1',
            'parameter5MinTherapeutic' => 'nullable|numeric|min:1',
            'parameter5MaxTherapeutic' => 'nullable|numeric|min:1',

            'parameter6MinSafety' => 'nullable|numeric|min:1',
            'parameter6MaxSafety' => 'nullable|numeric|min:1',
            'parameter6MinTherapeutic' => 'nullable|numeric|min:1',
            'parameter6MaxTherapeutic' => 'nullable|numeric|min:1',

            'parameter1times' =>  'nullable|numeric|min:1',
            'parameter1per' => 'nullable|in:hour,day,week,month',

            'parameter2times' =>  'nullable|numeric|min:1',
            'parameter2per' => 'nullable|in:hour,day,week,month',

            'parameter3times' =>  'nullable|numeric|min:1',
            'parameter3per' => 'nullable|in:hour,day,week,month',

            'parameter4times' =>  'nullable|numeric|min:1',
            'parameter4per' => 'nullable|in:hour,day,week,month',

            'parameter5times' =>  'nullable|numeric|min:1',
            'parameter5per' => 'nullable|in:hour,day,week,month',

            'parameter6times' =>  'nullable|numeric|min:1',
            'parameter6per' => 'nullable|in:hour,day,week,month',

            'parameter7times' =>  'nullable|numeric|min:1',
            'parameter7per' => 'nullable|in:hour,day,week,month',

            'recommendations' => 'nullable|string',

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

        $doctorId = 143;

        // create external user
        $responsePatient = Http::post(
            'http://147.175.106.7:5000/api/patient/createExternal',
            [
                'email' => $validated['email'],
                'name' => $validated['name'] ?? null, // email
                'lastname' => $validated['surname'] ?? null, // email
                'username' => $validated['email'],
                'password' => $validated['password'],
                'type' => 'patient',
                'doctorId' => $doctorId,
            ]
        );

        $responseMac = Http::post(
            'http://147.175.106.7:5000/api/patient/addSensor',
            [
                'MAC' => $validated['mac'],
                'patientId' => intval($responsePatient->body()),
            ]
        );

        $dateOfBirth = $request['birthDay'] && $request['birthMonth'] && $request['birthYear'] ? strval($validated['birthYear']) . '-' . strval($validated['birthMonth']) . '-' . strval($validated['birthDay']) : null;
        $patient = User::create([
            'id_external' => $responsePatient->body() ?? null,
            'name' => $validated['name'] ?? null,
            'surname' =>  $validated['surname'] ?? null,
            'sex' => $validated['sex'] ?? null,
            'dob' =>  $dateOfBirth ?? null,
            'height' =>  $validated['height'] ?? null,
            'weight' =>  $validated['weight'] ?? null,
            'email' =>  $validated['email'],
            'mobile' =>  $validated['mobile'] ?? null,
            'recommendations' => $validated['recommendations'] ?? null,
            'coordinator_id' => $validated['coordinatorId'],
            'password' => Hash::make($validated['password']),
            'is_coordinator' => false,
            'mac' => $validated['mac'] ?? null,
            'id_external_doctor' =>  $doctorId,
        ]);

        if ($request->parameter1check) {
            $patient->parameters()->attach(
                1,
                [
                    'measurement_times' => $validated['parameter1times'] ? $validated['parameter1per'] : Parameter::find(1)->measurement_times,
                    'measurement_span' => $validated['parameter1per'] ? $validated['parameter1per'] :Parameter::find(1)->measurement_span,
                    'threshold_safety_max' => $validated['parameter1MaxSafety'],
                    'threshold_safety_min' => $validated['parameter1MinSafety'],
                    'threshold_therapeutic_max' => $validated['parameter1MaxTherapeutic'],
                    'threshold_therapeutic_min' => $validated['parameter1MinTherapeutic'],
                ]
            );
        }

        if ($request->parameter2check) {
            $patient->parameters()->attach(
                2,
                [
                    'measurement_times' => $validated['parameter2times'] ? $validated['parameter2per'] : Parameter::find(2)->measurement_times,
                    'measurement_span' => $validated['parameter2per'] ? $validated['parameter2per'] :Parameter::find(2)->measurement_span,
                    'threshold_safety_max' => $validated['parameter2MaxSafety'],
                    'threshold_safety_min' => $validated['parameter2MinSafety'],
                    'threshold_therapeutic_max' => $validated['parameter2MaxTherapeutic'],
                    'threshold_therapeutic_min' => $validated['parameter2MinTherapeutic'],
                ]
            );
        }

        if ($request->parameter3check) {
            $patient->parameters()->attach(
                3,
                [
                    'measurement_times' => $validated['parameter3times'] ? $validated['parameter3per'] : Parameter::find(3)->measurement_times,
                    'measurement_span' => $validated['parameter3per'] ? $validated['parameter3per'] :Parameter::find(3)->measurement_span,
                    'threshold_safety_max' => $validated['parameter3MaxSafety'],
                    'threshold_safety_min' => $validated['parameter3MinSafety'],
                    'threshold_therapeutic_max' => $validated['parameter3MaxTherapeutic'],
                    'threshold_therapeutic_min' => $validated['parameter3MinTherapeutic'],
                ]
            );
        }

        if ($request->parameter4check) {
            $patient->parameters()->attach(
                4,
                [
                    'measurement_times' => $validated['parameter4times'] ? $validated['parameter4per'] : Parameter::find(4)->measurement_times,
                    'measurement_span' => $validated['parameter4per'] ? $validated['parameter4per'] :Parameter::find(4)->measurement_span,
                    'threshold_safety_max' => $validated['parameter4MaxSafety'],
                    'threshold_safety_min' => $validated['parameter4MinSafety'],
                    'threshold_therapeutic_max' => $validated['parameter4MaxTherapeutic'],
                    'threshold_therapeutic_min' => $validated['parameter4MinTherapeutic'],
                ]
            );
        }

        if ($request->parameter5check) {
            $patient->parameters()->attach(
                5,
                [
                    'measurement_times' => $validated['parameter5times'] ? $validated['parameter5per'] : Parameter::find(5)->measurement_times,
                    'measurement_span' => $validated['parameter5per'] ? $validated['parameter5per'] :Parameter::find(5)->measurement_span,
                    'threshold_safety_max' => $validated['parameter5MaxSafety'],
                    'threshold_safety_min' => $validated['parameter5MinSafety'],
                    'threshold_therapeutic_max' => $validated['parameter5MaxTherapeutic'],
                    'threshold_therapeutic_min' => $validated['parameter5MinTherapeutic'],
                ]
            );
        }


        if ($request->parameter6check) {
            $patient->parameters()->attach(
                6,
                [
                    'measurement_times' => $validated['parameter6times'] ? $validated['parameter6per'] : Parameter::find(6)->measurement_times,
                    'measurement_span' => $validated['parameter6per'] ? $validated['parameter6per'] :Parameter::find(6)->measurement_span,
                    'threshold_safety_max' => $validated['parameter6MaxSafety'],
                    'threshold_safety_min' => $validated['parameter6MinSafety'],
                    'threshold_therapeutic_max' => $validated['parameter6MaxTherapeutic'],
                    'threshold_therapeutic_min' => $validated['parameter6MinTherapeutic'],
                ]
            );
        }

        if ($request->parameter7check) {
            $patient->parameters()->attach(
                7,
                [
                    'measurement_times' => $validated['parameter7times'] ? $validated['parameter7per'] : Parameter::find(7)->measurement_times,
                    'measurement_span' => $validated['parameter7per'] ? $validated['parameter7per'] :Parameter::find(7)->measurement_span,
                ]
            );
        }

        if ($request->drug1) {
            $patient->drugs()->attach(
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
            $patient->drugs()->attach(
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
            $patient->drugs()->attach(
                3,
                [
                    'dosage_volume' => $validated['drug3volume'],
                    'dosage_unit' => $validated['drug3unit'],
                    'dosage_times' => $validated['drug3times'],
                    'dosage_span' => $validated['drug3per'],
                ]
            );
        }

        if ($request->conditions) {
            foreach ($request->conditions as $conditionId) {
                $patient->conditions()->attach($conditionId);
            }
        }

        $locale = $request->getPreferredLanguage(['en', 'sk']);
        if ($patient) {
            if ($locale == 'sk') {
                flash('Pacient ' . $patient->name . ' ' . $patient->surname . ' bol úspešne pridaný.')->success();
            } else {
                flash('Patient ' . $patient->name . ' ' . $patient->surname . ' was successfully added.')->success();
            }
        } else {
            if ($locale == 'sk') {
                flash('Pacienta ' . $patient->name . ' ' . $patient->surname . ' sa nepodarilo pridať.')->success();
            } else {
                flash('Patient ' . $patient->name . ' ' . $patient->surname . ' could not be added.')->success();
            }
        }

        return redirect()->action([ProfileController::class, 'index'], ['patient' => $patient->id]);
    }


    public function deactivate(Request $request)
    {
        User::destroy($request->route('patient'));
        $locale = $request->getPreferredLanguage(['en', 'sk']);
        if ($locale == 'sk') {
            flash('Používateľov účet bol úspešne deaktivovaný.')->success();

        } else {
            flash('The account was successfully deactivated.')->success();
        }

        return redirect()->action([PatientController::class, 'index']);
    }

    public function restore(Request $request)
    {
        $user = User::where('id', $request->route('patient'))->restore();
        $locale = $request->getPreferredLanguage(['en', 'sk']);
        if ($locale == 'sk') {
            flash('Používateľov účet bol úspešne obnovený.')->success();

        } else {
            flash('The account was successfully restored.')->success();
        }

        return redirect()->action([PatientController::class, 'index']);
    }

    function storeEcg(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make(
            $params,
            [
                'file' => 'required|mimes:zip',
                'patientId' => 'required|exists:users,id_external'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $patient = User::where('id_external', $request->patientId)->first();

        $filePath = $request->file->getPathName();
        $parser = new Parser();
        $ecgParsedArray = $parser->parse($filePath);

        $ecgIds = array();
        foreach ($ecgParsedArray as $ecgParsed) {
            $ecgDate = Carbon::createFromTimestampMs($ecgParsed['timestamp']);
            $values = implode(',', $ecgParsed['values']);
            $eventsE = implode(',', $ecgParsed['eventsP']);
            $eventsB = implode(',', $ecgParsed['eventsB']);
            $eventsT = implode(',', $ecgParsed['eventsT']);
            $eventsAF = implode(',', $ecgParsed['eventsAF']);
            $createdAt = $ecgDate;

            $ecg = ECG::create([
                'user_id' => $patient->id,
                'values' => $values,
                'eventsE' => $eventsE,
                'eventsB' => $eventsB,
                'eventsT' => $eventsT,
                'eventsAF' => $eventsAF,
                'created_at' => $createdAt,
            ]);

            array_push($ecgIds, $ecg->id);
        }

        return $ecgIds;
    }

    public function storeEcgWithQuery(Request $request)
    {
        $params = $request->all();
        $params['patient'] = $request->route('patient');

        $validator = Validator::make(
            $params,
            [
                'file' => 'required|mimes:zip',
                'patient' => 'required|exists:users,id_external'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $patient = User::where('id_external', $params['patient'])->first();

        $filePath = $request->file->getPathName();
        $parser = new Parser();
        $ecgParsedArray = $parser->parse($filePath);

        $ecgIds = array();
        foreach ($ecgParsedArray as $ecgParsed) {
            $ecgDate = Carbon::createFromTimestampMs($ecgParsed['timestamp']);
            $userId = $patient->id;
            $values = implode(',', $ecgParsed['values']);
            $eventsE = implode(',', $ecgParsed['eventsP']);
            $eventsB = implode(',', $ecgParsed['eventsB']);
            $eventsT = implode(',', $ecgParsed['eventsT']);
            $eventsAF = implode(',', $ecgParsed['eventsAF']);
            $createdAt = $ecgDate;

            $ecg = ECG::create([
                'user_id' => $patient->id,
                'values' => $values,
                'eventsE' => $eventsE,
                'eventsB' => $eventsB,
                'eventsT' => $eventsT,
                'eventsAF' => $eventsAF,
                'created_at' => $createdAt,
            ]);

            array_push($ecgIds, $ecg->id);
        }

        return $ecgIds;
    }
}

<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $patient = User::where('id', $request->route('patient'))->first();
        return view('coordinator.patients.profile.index', ['patient' => $patient]);
    }

    public function therapy(Request $request)
    {
        $user = Auth::user();
        $patient = User::where('id', $request->route('patient'))->first();
        $thresholds = $patient->thresholds();
        $parameters = $patient->parameters;
        $conditions = $patient->conditions;
        $drugs = $patient->drugs;
        return view('coordinator.patients.therapy.index', ['patient' => $patient, 'parameters' => $parameters, 'conditions' => $conditions, 'drugs' => $drugs, 'thresholds' => $thresholds]);
    }
}

<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        return view('patient.profile.index', $user);
    }

    public function therapy(Request $request)
    {
        $user = Auth::user();
        $parameters = Auth::user()->parameters;
        $conditions = Auth::user()->conditions;
        $drugs = Auth::user()->drugs;
        return view('patient.therapy.index', ['user' => $user, 'parameters' => $parameters, 'conditions' => $conditions, 'drugs' => $drugs]);
    }
}

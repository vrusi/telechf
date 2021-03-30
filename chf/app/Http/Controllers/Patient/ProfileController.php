<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        return view('patient.profile.index', $user);
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        return view('patient.profile.create', $user);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'nullable|string',
            'mobile' => 'nullable|string',
            ]);
            
        $user = User::where('id', Auth::user()->id)->first();
        $user->email = $request->email ?? $user->email;
        $user->mobile = $request->mobile ?? $user->mobile;
        $user->save();

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

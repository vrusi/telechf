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
        $auth = Auth::user();
        $user = User::where('id', $auth->id)->first();
        return view('patient.profile.index', ['user' => $user]);
    }

    public function create(Request $request)
    {
        $auth = Auth::user();
        $user = User::where('id', $auth->id)->first();
        return view('patient.profile.create', ['user' => $user]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'nullable|string',
            'mobile' => 'nullable|string',
        ]);

        $auth = Auth::user();
        $user = User::where('id', $auth->id)->first();
        $user->email = $validated['email'] ?? $user->email;
        $user->mobile = $validated['mobile'] ?? $user->mobile;
        $response = $user->save();

        if ($request) {
            flash('Your personal information was successfully edited')->success();
        } else {
            flash('Something went wrong.')->error();
        }

        return view('patient.profile.index', ['user' => $user]);
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

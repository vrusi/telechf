<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $coordinator = Auth::user();
        $patients = $coordinator->patients; 
        return view('coordinator.patients.index', [
            'patients' => $patients,
        ]);
    }

    public function show(Request $request)
    {
        $coordinator = Auth::user();
        $patient =  User::where('id', $request->route('patient'))->first(); 
        return view('coordinator.patients.show', [
            'patient' => $patient,
        ]);
    }
}

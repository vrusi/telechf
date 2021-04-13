<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $patient = User::where('id', $request->route('patient'))->first();
        $contacts = $patient->contacts;
        return view('coordinator.patients.contact.index', ['patient' => $patient, 'contacts' => $contacts]);
    }

    public function create(Request $request)
    {
        $patient = User::where('id', $request->route('patient'))->first();
        return view('coordinator.patients.contact.create', ['patient' => $patient]);
    }
}

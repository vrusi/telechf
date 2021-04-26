<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Contact;
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
        $contacts = Contact::all();
        $patient = User::where('id', $request->route('patient'))->first();
        return view('coordinator.patients.contact.create', [
            'patient' => $patient,
            'contacts' => $contacts,
        ]);
    }

    public function store(Request $request)
    {

        $validated = null;

        if ($request->contactId != null) {

            $validated = $request->validate([
                'contactId' => 'required|exists:contacts,id',
                'patientId' => 'required|exists:users,id'
            ]);

            $patient = User::where('id', $validated['patientId'])->first();
            $contact = $patient->contacts()->where('contact_id', $validated['contactId'])->first();
            
            if (!$contact) {
                $patient->contacts()->attach($validated['contactId']);
            }

            return redirect()->action([ContactController::class, 'index'], ['patient' => $patient->id]);
        } else {
            $validated = $request->validate([
                'titles_prefix' => 'nullable|string',
                'name' => 'required',
                'surname' => 'required',
                'titles_postfix' => 'nullable|string',
                'email' => 'required|string',
                'mobile' => 'required|string',
                'type' => 'required|in:1,2',
                'patientId' => 'required|exists:users,id'
            ]);

            $patient = User::where('id', $validated['patientId'])->first();
            $contactId = Contact::create([
                'titles_prefix' => $validated['titles_prefix'],
                'name' => $validated['name'],
                'surname' => $validated['surname'],
                'titles_postfix' => $validated['titles_postfix'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
                'type' => $validated['type'] == "1" ? 'general practitioner' : 'cardiologist',
            ]);
            
            $patient->contacts()->attach($contactId);
            return redirect()->action([ContactController::class, 'index'], ['patient' => $patient->id]);
        }
    }
}

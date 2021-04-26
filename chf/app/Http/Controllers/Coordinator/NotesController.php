<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use App\Models\Note;
use App\Models\Parameter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
{
    public function index(Request $request)
    {
        $patient = User::where('id', $request->route('patient'))->first();
        $date = $request->query('date');
        $date = $date ? Carbon::createFromFormat('d M',  $date) : null;
        $parameters = Parameter::orderBy('id', 'ASC')->get();

        // return all notes if no date was specified
        $measurements = null;
        if (!$date) {
            $measurements = $patient->measurements;

            return view('coordinator.patients.measurements.notes.index', [
                'patient' => $patient,
                'notesAll' => null,
                'date' => null,
                'parameters' => $parameters,
                'measurements' => null,
            ]);
            
        } else {
            $measurements = $patient->measurementsInDay($date);
        }


        // fill the array with nulls for proper sorting in the table
        $measurementsPadded = array_fill(1, count($parameters) - 1, null);
        foreach ($parameters as $parameter) {
            foreach ($measurements as $measurement) {

                if (!is_array($measurement)) {
                    $measurement = $measurement->toArray();
                }

                // fill the values in their respective places
                if (array_key_exists('parameter_id', $measurement)) {
                    if ($measurement['parameter_id'] == $parameter->id) {
                        $measurementsPadded[$parameter->id] = $measurement;
                    }
                };
            }
        }

        $notesAll = array();
        foreach ($measurementsPadded as $measurement) {
            if (!$measurement || !key_exists('id', $measurement)) {
                continue;
            }

            $measurementId = $measurement['id'];
            $notes = Measurement::find($measurementId)->notes;
            
            if (count($notes) > 0) {
                array_push($notesAll, ['measurement' => $measurement, 'notes' => $notes]);
            }
        }

        return view('coordinator.patients.measurements.notes.index', [
            'patient' => $patient,
            'notesAll' => $notesAll,
            'date' => $date,
            'parameters' => $parameters,
            'measurements' => $measurementsPadded,
        ]);
    }

    public function store(Request $request)
    {
        $author = Auth::user();

        $validated = $request->validate([
            'measurementSelect' => 'required|exists:measurements,id',
            'note' => 'required',
        ]);

        $noteId = Note::create([
            'measurement_id' => $validated['measurementSelect'],
            'author_id' => $author->id,
            'value' => $validated['note'],
        ]);

        if ($noteId) {
            flash('The note was successfully created')->success();
        } else {
            flash('The note was not created.')->error();
        };

        $date = $request->query('date');

        return redirect()->action([NotesController::class, 'index'], ['patient' => $request->patientId, 'date' => $date]);
    }
}

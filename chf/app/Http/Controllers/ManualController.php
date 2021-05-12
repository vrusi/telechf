<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManualController extends Controller
{
    function index() {
        if (Auth::user()->is_coordinator) {
            return view('coordinator.manual.index');
        } else {
            return view('patient.manual.index');
        }
    }
}

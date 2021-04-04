<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr as SupportArr;
use Illuminate\Support\Facades\Auth;
use Minwork\Helper\Arr;

class DashboardController extends Controller
{
    function index(Request $request)
    {
        $user = Auth::user();
        $parameters = $user->parameters->toArray();
        $summary = $user->measurementsSummary();
        $alarms = $user->measurementsAlarms();
        return view('patient.dashboard.index', ['summary' => $summary, 'alarms' => $alarms, 'parameters' => $parameters]);
    }
}

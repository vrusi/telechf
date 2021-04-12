<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Parameter;
use Illuminate\Http\Request;

class ThresholdController extends Controller
{
    public function index(Request $request)
    {
        $parameters = Parameter::orderBy('id', 'ASC')->get();
        return view('coordinator.thresholds.index', ['parameters' => $parameters]);
    }

    public function create(Request $request)
    {
        $parameters = Parameter::orderBy('id', 'ASC')->get();
        return view('coordinator.thresholds.create', ['parameters' => $parameters]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parameter1min' => 'nullable|numeric|min:1',
            'parameter1max' => 'nullable|numeric|min:1',
            'parameter1times' => 'nullable|numeric|min:1',
            'parameter1per' => 'nullable|in:hour,day,week,month',
            'parameter2min' => 'nullable|numeric|min:1',
            'parameter2max' => 'nullable|numeric|min:1',
            'parameter2times' => 'nullable|numeric|min:1',
            'parameter2per' => 'nullable|in:hour,day,week,month',
            'parameter3min' => 'nullable|numeric|min:1',
            'parameter3max' => 'nullable|numeric|min:1',
            'parameter3times' => 'nullable|numeric|min:1',
            'parameter3per' => 'nullable|in:hour,day,week,month',
            'parameter4min' => 'nullable|numeric|min:1',
            'parameter4max' => 'nullable|numeric|min:1',
            'parameter4times' => 'nullable|numeric|min:1',
            'parameter4per' => 'nullable|in:hour,day,week,month',
            'parameter5min' => 'nullable|numeric|min:1',
            'parameter5max' => 'nullable|numeric|min:1',
            'parameter5times' => 'nullable|numeric|min:1',
            'parameter5per' => 'nullable|in:hour,day,week,month',
            'parameter6min' => 'nullable|numeric|min:1',
            'parameter6max' => 'nullable|numeric|min:1',
            'parameter6times' => 'nullable|numeric|min:1',
            'parameter6per' => 'nullable|in:hour,day,week,month',
            'parameter7times' => 'nullable|numeric|min:1',
            'parameter7per' => 'nullable|in:hour,day,week,month',
        ]);

        $parameter1 =  Parameter::where('id', 1)->first();
        Parameter::where('id', 1)
            ->update(
                [
                    'threshold_min' => $request->parameter1minCheck ? null : ($request->parameter1min ?? $parameter1->threshold_min),
                    'threshold_max' => $request->parameter1maxCheck ? null : ($request->parameter1max ?? $parameter1->threshold_max),
                    'measurement_times' => $request->parameter1freqCheck ? null : ($request->parameter1times ?? $parameter1->measurement_times),
                    'measurement_span' => $request->parameter1freqCheck ? null : ($request->parameter1per ?? $parameter1->measurement_span),
                ]
            );

        $parameter2 =  Parameter::where('id', 2)->first();
        Parameter::where('id', 2)
            ->update(
                [
                    'threshold_min' => $request->parameter2minCheck ? null : ($request->parameter2min ?? $parameter2->threshold_min),
                    'threshold_max' => $request->parameter2maxCheck ? null : ($request->parameter2max ?? $parameter2->threshold_max),
                    'measurement_times' => $request->parameter2freqCheck ? null : ($request->parameter2times ?? $parameter2->measurement_times),
                    'measurement_span' => $request->parameter2freqCheck ? null : ($request->parameter2per ?? $parameter2->measurement_span),
                ]
            );

        $parameter3 =  Parameter::where('id', 3)->first();
        Parameter::where('id', 3)
            ->update(
                [
                    'threshold_min' => $request->parameter3minCheck ? null : ($request->parameter3min ?? $parameter3->threshold_min),
                    'threshold_max' => $request->parameter3maxCheck ? null : ($request->parameter3max ?? $parameter3->threshold_max),
                    'measurement_times' => $request->parameter3freqCheck ? null : ($request->parameter3times ?? $parameter3->measurement_times),
                    'measurement_span' => $request->parameter3freqCheck ? null : ($request->parameter3per ?? $parameter3->measurement_span),
                ]
            );

        $parameter4 =  Parameter::where('id', 4)->first();
        Parameter::where('id', 4)
            ->update(
                [
                    'threshold_min' => $request->parameter4minCheck ? null : ($request->parameter4min ?? $parameter4->threshold_min),
                    'threshold_max' => $request->parameter4maxCheck ? null : ($request->parameter4max ?? $parameter4->threshold_max),
                    'measurement_times' => $request->parameter4freqCheck ? null : ($request->parameter4times ?? $parameter4->measurement_times),
                    'measurement_span' => $request->parameter4freqCheck ? null : ($request->parameter4per ?? $parameter4->measurement_span),
                ]
            );

        $parameter5 =  Parameter::where('id', 5)->first();
        Parameter::where('id', 5)
            ->update(
                [
                    'threshold_min' => $request->parameter5minCheck ? null : ($request->parameter5min ?? $parameter5->threshold_min),
                    'threshold_max' => $request->parameter5maxCheck ? null : ($request->parameter5max ?? $parameter5->threshold_max),
                    'measurement_times' => $request->parameter5freqCheck ? null : ($request->parameter5times ?? $parameter5->measurement_times),
                    'measurement_span' => $request->parameter5freqCheck ? null : ($request->parameter5per ?? $parameter5->measurement_span),
                ]
            );

        $parameter6 =  Parameter::where('id', 6)->first();
        Parameter::where('id', 6)
            ->update(
                [
                    'threshold_min' => $request->parameter6minCheck ? null : ($request->parameter6min ?? $parameter6->threshold_min),
                    'threshold_max' => $request->parameter6maxCheck ? null : ($request->parameter6max ?? $parameter6->threshold_max),
                    'measurement_times' => $request->parameter6freqCheck ? null : ($request->parameter6times ?? $parameter6->measurement_times),
                    'measurement_span' => $request->parameter6freqCheck ? null : ($request->parameter6per ?? $parameter6->measurement_span),
                ]
            );

        $parameter7 =  Parameter::where('id', 7)->first();
        Parameter::where('id', 7)
            ->update(
                [
                    'threshold_min' => $request->parameter7minCheck ? null : ($request->parameter7min ?? $parameter7->threshold_min),
                    'threshold_max' => $request->parameter7maxCheck ? null : ($request->parameter7max ?? $parameter7->threshold_max),
                    'measurement_times' => $request->parameter7freqCheck ? null : ($request->parameter7times ?? $parameter7->measurement_times),
                    'measurement_span' => $request->parameter7freqCheck ? null : ($request->parameter7per ?? $parameter7->measurement_span),
                ]
            );


        flash('The thresholds have been updated.')->success();
        return redirect()->action([ThresholdController::class, 'index']);
    }
}

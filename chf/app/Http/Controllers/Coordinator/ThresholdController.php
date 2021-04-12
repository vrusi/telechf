<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Parameter;
use Illuminate\Http\Request;

class ThresholdController extends Controller
{
    public function index(Request $request)
    {
        $parameters = Parameter::all();
        return view('coordinator.thresholds.index', ['parameters' => $parameters]);
    }

    public function create(Request $request)
    {
        $parameters = Parameter::all();
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

        Parameter::where('id', 1)
            ->update(
                [
                    'threshold_min' => $request->parameter1minCheck ?  null : ($request->parameter1min ?? Parameter::where('id', 1)->first()->threshold_min),
                    'threshold_max' => $request->parameter1maxCheck ?  null : ($request->parameter1max  ?? Parameter::where('id', 1)->first()->threshold_max),
                    'measurement_times' => $request->parameter1freqCheck ? null : ($request->parameter1times  ?? Parameter::where('id', 1)->first()->measurement_times),
                    'measurement_span' => $request->parameter1freqCheck ? null : ($request->parameter1per  ?? Parameter::where('id', 1)->first()->measurement_span),
                ]
            );

        Parameter::where('id', 2)
            ->update(
                [
                    'threshold_min' => $request->parameter2minCheck ?  null : ($request->parameter2min ?? Parameter::where('id', 2)->first()->threshold_min),
                    'threshold_max' => $request->parameter2maxCheck ?  null : ($request->parameter2max  ?? Parameter::where('id', 2)->first()->threshold_max),
                    'measurement_times' => $request->parameter2freqCheck ? null : ($request->parameter2times  ?? Parameter::where('id', 2)->first()->measurement_times),
                    'measurement_span' => $request->parameter2freqCheck ? null : ($request->parameter2per  ?? Parameter::where('id', 2)->first()->measurement_span),
                ]
            );

        Parameter::where('id', 3)
            ->update(
                [
                    'threshold_min' => $request->parameter3minCheck ?  null : ($request->parameter3min ?? Parameter::where('id', 3)->first()->threshold_min),
                    'threshold_max' => $request->parameter3maxCheck ?  null : ($request->parameter3max  ?? Parameter::where('id', 3)->first()->threshold_max),
                    'measurement_times' => $request->parameter3freqCheck ? null : ($request->parameter3times  ?? Parameter::where('id', 3)->first()->measurement_times),
                    'measurement_span' => $request->parameter3freqCheck ? null : ($request->parameter3per  ?? Parameter::where('id', 3)->first()->measurement_span),
                ]
            );

        Parameter::where('id', 4)
            ->update(
                [
                    'threshold_min' => $request->parameter4minCheck ?  null : ($request->parameter4min ?? Parameter::where('id', 4)->first()->threshold_min),
                    'threshold_max' => $request->parameter4maxCheck ?  null : ($request->parameter4max  ?? Parameter::where('id', 4)->first()->threshold_max),
                    'measurement_times' => $request->parameter4freqCheck ? null : ($request->parameter4times  ?? Parameter::where('id', 4)->first()->measurement_times),
                    'measurement_span' => $request->parameter4freqCheck ? null : ($request->parameter4per  ?? Parameter::where('id', 4)->first()->measurement_span),
                ]
            );

        Parameter::where('id', 5)
            ->update(
                [
                    'threshold_min' => $request->parameter5minCheck ?  null : ($request->parameter5min ?? Parameter::where('id', 5)->first()->threshold_min),
                    'threshold_max' => $request->parameter5maxCheck ?  null : ($request->parameter5max  ?? Parameter::where('id', 5)->first()->threshold_max),
                    'measurement_times' => $request->parameter5freqCheck ? null : ($request->parameter5times  ?? Parameter::where('id', 5)->first()->measurement_times),
                    'measurement_span' => $request->parameter5freqCheck ? null : ($request->parameter5per  ?? Parameter::where('id', 5)->first()->measurement_span),
                ]
            );

        Parameter::where('id', 6)
            ->update(
                [
                    'threshold_min' => $request->parameter6minCheck ?  null : ($request->parameter6min ?? Parameter::where('id', 6)->first()->threshold_min),
                    'threshold_max' => $request->parameter6maxCheck ?  null : ($request->parameter6max  ?? Parameter::where('id', 6)->first()->threshold_max),
                    'measurement_times' => $request->parameter6freqCheck ? null : ($request->parameter6times  ?? Parameter::where('id', 6)->first()->measurement_times),
                    'measurement_span' => $request->parameter6freqCheck ? null : ($request->parameter6per  ?? Parameter::where('id', 6)->first()->measurement_span),
                ]
            );

        Parameter::where('id', 7)
            ->update(
                [
                    'threshold_min' => $request->parameter7minCheck ?  null : ($request->parameter7min ?? Parameter::where('id', 7)->first()->threshold_min),
                    'threshold_max' => $request->parameter7maxCheck ?  null : ($request->parameter7max  ?? Parameter::where('id', 7)->first()->threshold_max),
                    'measurement_times' => $request->parameter7freqCheck ? null : ($request->parameter7times  ?? Parameter::where('id', 7)->first()->measurement_times),
                    'measurement_span' => $request->parameter7freqCheck ? null : ($request->parameter7per  ?? Parameter::where('id', 7)->first()->measurement_span),
                ]
            );

        flash('The thresholds have been updated.')->success();
        return redirect()->action([ThresholdController::class, 'index']);
    }
}

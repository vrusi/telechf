@extends('layouts.app')

@section('title', 'Profile')

@section('content')

    <div class="container patient">

        <h1 class="mb-3">
            {{ __('Your therapy') }}
        </h1>

        <div class="my-5">
            <h2>
                {{ __('Monitored parameters') }}
            </h2>

            <table>
                <thead>
                    <tr>
                        <th>
                            {{ __('Parameter') }}
                        </th>
                        <th>
                            {{ __('Goal values') }}
                        </th>
                        <th>
                            {{ __('Measurement frequency') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($parameters as $parameter)
                        <tr>
                            <td>
                                {{ __($parameter->name) }}
                            </td>

                            <td class="px-3">
                                {{-- both min and max --}}
                                @if ($parameter->pivot->threshold_therapeutic_min && $parameter->pivot->threshold_therapeutic_max)
                                    {{ $parameter->pivot->threshold_therapeutic_min }} -
                                    {{ $parameter->pivot->threshold_therapeutic_max }} {{ __($parameter->unit) }}

                                    {{-- only min --}}
                                @elseif($parameter->pivot->threshold_therapeutic_min &&
                                    !$parameter->pivot->threshold_therapeutic_max)
                                    ≥ {{ $parameter->pivot->threshold_therapeutic_min }} {{ __($parameter->unit) }}

                                    {{-- only max --}}
                                @elseif(!$parameter->pivot->threshold_therapeutic_min &&
                                    $parameter->pivot->threshold_therapeutic_max)
                                    ≤ {{ $parameter->pivot->threshold_therapeutic_max }} {{ __($parameter->unit) }}

                                    {{-- neither --}}
                                @else
                                    --
                                @endif
                            </td>

                            <td>
                                @if ($parameter->measurement_times)
                                    @if ($parameter->measurement_times == 1)
                                        {{ __('once per') . ' ' . __($parameter->measurement_span) }}
                                    @endif
                                    @if ($parameter->measurement_times == 2)
                                        {{ __('twice per') . ' ' . __($parameter->measurement_span) }}
                                    @endif
                                    @if ($parameter->measurement_times >= 3)
                                        {{ $parameter->measurement_times . __('times per') . ' ' . __($parameter->measurement_span) }}
                                    @endif
                                @endif

                                @if (!$parameter->measurement_times)
                                    {{ '--' }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($user->recommendations)
            <div class="my-5">
                <h2>
                    {{ __('Recommendations') }}
                </h2>
                <div>
                    {!! $user->recommendations !!}
                </div>
            </div>
        @endif

        <div class="my-5">
            <h2>
                @if ($user->sex == 'male')
                    {{ __('You are being treated for') }}
                @else
                    {{ __('You are being treated for ') }}
                @endif
            </h2>

            @foreach ($conditions as $condition)
                <div x-data="{ descriptionOpen: false }">

                    <div @click="descriptionOpen=!descriptionOpen" class="d-flex align-items-center" data-toggle="collapse"
                        data-target="{{ '#conditionDescription' . $condition->id }}" aria-expanded="false"
                        aria-controls="conditionDescription">
                        <div class="mr-3">
                            {{ ucfirst(trans(__($condition->name))) }}
                        </div>

                        <i x-show="!descriptionOpen" class="fas fa-caret-down"></i>
                        <i x-show="descriptionOpen" class="fas fa-caret-up"></i>

                    </div>
                </div>

                <div class="collapse" id="{{ 'conditionDescription' . $condition->id }}">
                    <div class="card card-body">
                        @if ($locale == 'en')
                            {!! $condition->description_en !!}
                        @elseif($locale == 'sk')
                            {!! $condition->description_sk !!}
                        @endif
                    </div>
                </div>
            @endforeach

        </div>
        <div class="my-5">
            <h2>
                {{ __('You are currently prescribed') }}
            </h2>
            <table>
                <thead>
                    <tr>
                        <th class="pr-3">
                            {{ __('Drug name') }}
                        </th>
                        <th class="pr-3">
                            {{ __('Dosage volume') }}
                        </th>
                        <th class="pr-3">
                            {{ __('Dosage frequency') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($drugs as $drug)
                        <tr>
                            <td class="pr-3">
                                {{ ucfirst(trans($drug->name)) }}
                            </td>

                            <td class="pr-3">
                                @if ($drug->pivot->dosage_volume && $drug->pivot->dosage_unit)
                                    {{ $drug->pivot->dosage_volume . ' ' . $drug->pivot->dosage_unit }}
                                @else
                                    --
                                @endif
                            </td>

                            <td class="pr-3">
                                @if ($drug->pivot->dosage_times)
                                    @if ($drug->pivot->dosage_times == 1)
                                        {{ __('once per') . ' ' . __($drug->pivot->dosage_span) }}
                                    @endif
                                    @if ($drug->pivot->dosage_times == 2)
                                        {{ __('twice per') . ' ' . __($drug->pivot->dosage_span) }}
                                    @endif
                                    @if ($drug->pivot->dosage_times >= 3)
                                        {{ $drug->pivot->dosage_times . __('times per') . ' ' . __($drug->pivot->dosage_span) }}
                                    @endif
                                @endif

                                @if (!$drug->pivot->dosage_times)
                                    {{ '--' }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection

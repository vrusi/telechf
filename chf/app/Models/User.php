<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Minwork\Helper\Arr;
use phpDocumentor\Reflection\Types\Boolean;
use Ramsey\Uuid\Type\Integer;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_external',
        'name',
        'surname',
        'email',
        'password',
        'mobile',
        'sex',
        'dob',
        'height',
        'weight',
        'is_coordinator',
        'coordinator_id',
        'recommendations',
        'mac',
        'id_external_doctor'
    ];

    private function mapConditions(int $value)
    {
        if ($value == 5) {
            return 'Very bad';
        } else if ($value == 4) {
            return 'Bad';
        } else if ($value == 3) {
            return 'Neutral';
        } else if ($value == 2) {
            return 'Good';
        } else if ($value == 1) {
            return 'Very good';
        }

        return null;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function age()
    {
        return Carbon::parse($this->dob)->copy()->age;
    }

    public function measurements()
    {
        return $this->hasMany(Measurement::class);
    }

    public function measurementsByDay()
    {
        return Measurement::where('user_id', $this->id)->orderBy('created_at', 'desc')->get()->groupBy(function ($measurement) {
            return $measurement->created_at->format('d M');
        })->toArray();
    }

    public function conditionsAveragesByDay()
    {
        $measurementsByDay = $this->measurementsByDay();

        $avgConditions = array();
        foreach ($measurementsByDay as $date => $day) {
            $swellings = 0;
            $swellingsCount = 0;

            $exercise = 0;
            $exerciseCount = 0;

            $dyspnoea = 0;
            $dyspnoeaCount = 0;

            foreach ($day as $measurement) {
                $swellings += $measurement['swellings'];
                $swellingsCount++;

                $exercise += $measurement['exercise_tolerance'];
                $exerciseCount++;

                $dyspnoea += $measurement['dyspnoea'];
                $dyspnoeaCount++;
            }

            $swellingsAvg = $swellingsCount > 0 ? $swellings / $swellingsCount : null;
            $exerciseAvg = $exerciseCount > 0 ? $exercise / $exerciseCount : null;
            $dyspnoeaAvg = $dyspnoeaCount > 0 ? $dyspnoea / $dyspnoeaCount : null;

            $avgConditions[$date] = [
                'swellings' => $swellingsAvg,
                'exercise' => $exerciseAvg,
                'dyspnoea' => $dyspnoeaAvg,
            ];
        }

        return $avgConditions;
    }

    public function conditionsCountsByDay()
    {
        $measurementsByDay = $this->measurementsByDay();
        $counts = array();
        foreach ($measurementsByDay as $date => $day) {
            $swellings1 = 0;
            $swellings2 = 0;
            $swellings3 = 0;
            $swellings4 = 0;
            $swellings5 = 0;
            $exercise1 = 0;
            $exercise2 = 0;
            $exercise3 = 0;
            $exercise4 = 0;
            $exercise5 = 0;
            $dyspnoea1 = 0;
            $dyspnoea2 = 0;
            $dyspnoea3 = 0;
            $dyspnoea4 = 0;
            $dyspnoea5 = 0;

            foreach ($day as $measurement) {
                $swellings = $measurement['swellings'];
                if ($swellings == 1) {
                    $swellings1++;
                } elseif ($swellings == 2) {
                    $swellings2++;
                } elseif ($swellings == 3) {
                    $swellings3++;
                } elseif ($swellings == 4) {
                    $swellings4++;
                } elseif ($swellings == 5) {
                    $swellings5++;
                }

                $exercise = $measurement['exercise_tolerance'];
                if ($exercise == 1) {
                    $exercise1++;
                } elseif ($exercise == 2) {
                    $exercise2++;
                } elseif ($exercise == 3) {
                    $exercise3++;
                } elseif ($exercise == 4) {
                    $exercise4++;
                } elseif ($exercise == 5) {
                    $exercise5++;
                }

                $dyspnoea = $measurement['dyspnoea'];
                if ($dyspnoea == 1) {
                    $dyspnoea1++;
                } elseif ($dyspnoea == 2) {
                    $dyspnoea2++;
                } elseif ($dyspnoea == 3) {
                    $dyspnoea3++;
                } elseif ($dyspnoea == 4) {
                    $dyspnoea4++;
                } elseif ($dyspnoea == 5) {
                    $dyspnoea5++;
                }
            }

            $counts[$date] =
                [
                    'swellings' => [
                        1 => $swellings1,
                        2 => $swellings2,
                        3 => $swellings3,
                        4 => $swellings4,
                        5 => $swellings5,
                    ],
                    'exercise' => [
                        1 => $exercise1,
                        2 => $exercise2,
                        3 => $exercise3,
                        4 => $exercise4,
                        5 => $exercise5,
                    ],
                    'dyspnoea' => [
                        1 => $dyspnoea1,
                        2 => $dyspnoea2,
                        3 => $dyspnoea3,
                        4 => $dyspnoea4,
                        5 => $dyspnoea5,
                    ],
                ];
        }

        return $counts;
    }


    public function extraMeasurementsByDay()
    {
        return Measurement::where('user_id', $this->id)->where('extra', true)->orderBy('created_at', 'desc')->get()->groupBy(function ($measurement) {
            return $measurement->created_at->format('d M');
        })->toArray();
    }

    public function measurementsSummary()
    {
        $parameters = $this->parameters->toArray();
        $measurementsGrouped = $this->measurementsByDay();

        return array_map(function ($measurementsPerDay) use ($parameters) {
            $values = array_map(function ($parameter) use ($measurementsPerDay) {
                $value = null;
                $alarmAny = false;
                $alarmSafetyMax = false;
                $alarmSafetyMin = false;
                $alarmTherapeuticMax = false;
                $alarmTherapeuticMin = false;
                $checked = false;
                $measurementId = null;
                $createdAt = null;
                $extra = null;

                foreach ($measurementsPerDay as $measurement) {
                    if ($measurement['parameter_id'] == $parameter['id']) {
                        $value = $measurement['value'];
                        $alarmAny = $measurement['triggered_safety_alarm_max'] || $measurement['triggered_safety_alarm_min'] || $measurement['triggered_therapeutic_alarm_max'] || $measurement['triggered_therapeutic_alarm_min'];
                        $alarmSafetyMax = $measurement['triggered_safety_alarm_max'];
                        $alarmSafetyMin = $measurement['triggered_safety_alarm_min'];
                        $alarmTherapeuticMax = $measurement['triggered_therapeutic_alarm_max'];
                        $alarmTherapeuticMin = $measurement['triggered_therapeutic_alarm_min'];
                        $checked = $measurement['checked'];
                        $measurementId = $measurement['id'];
                        $createdAt = $measurement['created_at'];
                        $extra = $measurement['extra'];
                    }
                }

                return [
                    'parameter' => $parameter['name'],
                    'value' => $value,
                    'unit' => $parameter['unit'],
                    'alarm' => $alarmAny,
                    'triggered_safety_alarm_max' => $alarmSafetyMax,
                    'triggered_safety_alarm_min' => $alarmSafetyMin,
                    'triggered_therapeutic_alarm_max' => $alarmTherapeuticMax,
                    'triggered_therapeutic_alarm_min' => $alarmTherapeuticMin,
                    'date' => $createdAt,
                    'checked' => $checked,
                    'extra' => $extra,
                    'measurementId' => $measurementId,
                ];
            }, $parameters);

            $conditions = Arr::map(['swellings' => 'Swellings', 'exercise_tolerance' => 'Exercise Tolerance', 'dyspnoea' => 'Dyspnoea while lying down'], function ($key, $name) use ($measurementsPerDay) {
                $avg = null;
                $avgMapped = '';
                // TODO
                $alarmAny = false;
                $alarmSafetyMax = false;
                $alarmSafetyMin = false;
                $alarmTherapeuticMax = false;
                $alarmTherapeuticMin = false;
                $checked = true;

                if (count($measurementsPerDay) > 0) {
                    $avg = 0;
                    foreach ($measurementsPerDay as $measurement) {
                        $avg = $avg + $measurement[$key] ?? 0;
                    }
                    $avg = $avg / count($measurementsPerDay);
                    $avgMapped = $this->mapConditions(ceil($avg));
                }

                return [
                    'name' => $name,
                    'value' => $avgMapped,
                    'alarm' => $alarmAny,
                    'triggered_safety_alarm_max' => $alarmSafetyMax,
                    'triggered_safety_alarm_min' => $alarmSafetyMin,
                    'triggered_therapeutic_alarm_max' => $alarmTherapeuticMax,
                    'triggered_therapeutic_alarm_min' => $alarmTherapeuticMin,
                    'checked' => $checked,
                    'measurementId' => $measurement['id'],
                ];
            });

            $conditions = array_values($conditions);

            return array_merge($values, $conditions);
        }, $measurementsGrouped);
    }

    public function measurementsAlarms()
    {
        $alarms = array();
        $summary = $this->measurementsSummary();
        foreach ($summary as $date => $day) {
            foreach ($day as $measurement) {

                if (!$measurement['alarm']) {
                    continue;
                }

                if (in_array($day, $alarms)) {
                    continue;
                }

                $alarms[$date] = $day;
            }
        }

        return $alarms;
    }

    public function measurementsExtra()
    {
        $parameters = $this->parameters->toArray();
        $measurementsGrouped = $this->extraMeasurementsByDay();

        return array_map(function ($measurementsPerDay) use ($parameters) {
            $values = array_map(function ($parameter) use ($measurementsPerDay) {
                $value = null;
                $alarmAny = false;
                $alarmSafetyMax = false;
                $alarmSafetyMin = false;
                $alarmTherapeuticMax = false;
                $alarmTherapeuticMin = false;
                $checked = false;
                $measurementId = null;
                $createdAt = null;
                $extra = null;
                $extraDescription = null;


                foreach ($measurementsPerDay as $measurement) {
                    if ($measurement['parameter_id'] == $parameter['id']) {
                        $value = $measurement['value'];
                        $alarmAny = $measurement['triggered_safety_alarm_max'] || $measurement['triggered_safety_alarm_min'] || $measurement['triggered_therapeutic_alarm_max'] || $measurement['triggered_therapeutic_alarm_min'];
                        $alarmSafetyMax = $measurement['triggered_safety_alarm_max'];
                        $alarmSafetyMin = $measurement['triggered_safety_alarm_min'];
                        $alarmTherapeuticMax = $measurement['triggered_therapeutic_alarm_max'];
                        $alarmTherapeuticMin = $measurement['triggered_therapeutic_alarm_min'];
                        $checked = $measurement['checked'];
                        $measurementId = $measurement['id'];
                        $createdAt = $measurement['created_at'];
                        $extra = $measurement['extra'];
                        $extraDescription = $measurement['extra_description'];
                    }
                }

                return [
                    'parameter' => $parameter['name'],
                    'value' => $value,
                    'unit' => $parameter['unit'],
                    'alarm' => $alarmAny,
                    'triggered_safety_alarm_max' => $alarmSafetyMax,
                    'triggered_safety_alarm_min' => $alarmSafetyMin,
                    'triggered_therapeutic_alarm_max' => $alarmTherapeuticMax,
                    'triggered_therapeutic_alarm_min' => $alarmTherapeuticMin,
                    'date' => $createdAt,
                    'checked' => $checked,
                    'extra' => $extra,
                    'measurementId' => $measurementId,
                    'extra_description' => $extraDescription,
                ];
            }, $parameters);

            $conditions = Arr::map(['swellings' => 'Swellings', 'exercise_tolerance' => 'Exercise Tolerance', 'dyspnoea' => 'Dyspnoea while lying down'], function ($key, $name) use ($measurementsPerDay) {
                $avg = null;
                $avgMapped = '';
                // TODO
                $alarmAny = false;
                $alarmSafetyMax = false;
                $alarmSafetyMin = false;
                $alarmTherapeuticMax = false;
                $alarmTherapeuticMin = false;
                $checked = true;

                if (count($measurementsPerDay) > 0) {
                    $avg = 0;
                    foreach ($measurementsPerDay as $measurement) {
                        $avg = $avg + $measurement[$key] ?? 0;
                    }
                    $avg = $avg / count($measurementsPerDay);

                    $avgMapped = $this->mapConditions(ceil($avg));
                }

                return [
                    'name' => $name,
                    'value' => $avgMapped,
                    'alarm' => $alarmAny,
                    'triggered_safety_alarm_max' => $alarmSafetyMax,
                    'triggered_safety_alarm_min' => $alarmSafetyMin,
                    'triggered_therapeutic_alarm_max' => $alarmTherapeuticMax,
                    'triggered_therapeutic_alarm_min' => $alarmTherapeuticMin,
                    'checked' => $checked,
                    'measurementId' => $measurement['id'],
                    'extra_description' => null,
                ];
            });

            $conditions = array_values($conditions);
            return array_merge($values, $conditions);
        }, $measurementsGrouped);
    }

    public function parameters()
    {
        return $this->belongsToMany(Parameter::class, 'user_parameters', 'user_id', 'parameter_id')->withPivot('threshold_safety_min', 'threshold_safety_max', 'threshold_therapeutic_min', 'threshold_therapeutic_max', 'measurement_times', 'measurement_span');
    }

    public function hasParameter(Parameter $parameterToCheck)
    {
        $parameters = $this->parameters;

        foreach ($parameters as $parameter) {
            if ($parameter->id == $parameterToCheck->id) {
                return true;
            }
        }

        return false;
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'user_contacts');
    }

    public function conditions()
    {
        return $this->belongsToMany(Condition::class, 'user_conditions');
    }

    public function drugs()
    {
        return $this->belongsToMany(Drug::class, 'user_drugs', 'user_id', 'drug_id')->withPivot('dosage_volume', 'dosage_unit', 'dosage_times', 'dosage_span');
    }

    public function thresholds()
    {
        $parametersGlobal =  Parameter::orderBy('id', 'ASC')->get();
        $parametersPersonal =  $this->parameters;

        $thresholds = array();

        foreach ($parametersPersonal as $personal) {
            $safetyMax = $personal->pivot->threshold_safety_max ?? null;
            $safetyMin = $personal->pivot->threshold_safety_min ?? null;

            $therapeuticMax = $personal->pivot->threshold_therapeutic_max ?? null;
            $therapeuticMin = $personal->pivot->threshold_therapeutic_min ?? null;

            if (!$safetyMax || !$safetyMin) {
                foreach ($parametersGlobal as $global) {
                    if ($global->id == $personal->id) {
                        $safetyMax = $safetyMax ?? $global->threshold_max;
                        $safetyMin = $safetyMin ?? $global->threshold_min;
                    }
                }
            }

            $thresholds[$personal->id] = [
                'safetyMax' => $safetyMax,
                'safetyMin' => $safetyMin,
                'therapeuticMax' => $therapeuticMax,
                'therapeuticMin' => $therapeuticMin
            ];
        }

        return $thresholds;
    }

    public function patients()
    {
        return $this->hasMany(User::class, 'coordinator_id');
    }

    public function ecgMeasurements()
    {
        return $this->hasMany(ECG::class, 'user_id');
    }

    public function measurementsInDay(object $createdAt)
    {
        $measurements = $this->measurements;
        $measurementsInDay = array();

        foreach ($measurements as $measurement) {
            if ($measurement->created_at->isSameDay($createdAt)) {
                array_push($measurementsInDay, $measurement);
            }
        }

        $conditions = Arr::map(['swellings' => 'Swellings', 'exercise_tolerance' => 'Exercise Tolerance', 'dyspnoea' => 'Dyspnoea while lying down'], function ($key, $name) use ($measurementsInDay) {
            $avg = null;
            $avgMapped = '';
            // TODO
            $alarm = false;
            $checked = true;

            if (count($measurementsInDay) > 0) {
                $avg = 0;
                foreach ($measurementsInDay as $measurement) {
                    $avg = $avg + $measurement[$key] ?? 0;
                }
                $avg = $avg / count($measurementsInDay);

                $avgMapped = $this->mapConditions(ceil($avg));
            }

            return [
                'name' => $name,
                'value' => $avgMapped,
                'triggered_safety_alarm_max' => false,
                'triggered_safety_alarm_min' => false,
                'triggered_therapeutic_alarm_max' => false,
                'triggered_therapeutic_alarm_min' => false,
                'checked' => $checked,
            ];
        });

        $conditions = array_values($conditions);

        return array_merge($measurementsInDay, $conditions);
    }

    public function isAnyMeasurementUncheckedInDay($createdAt)
    {
        if (!$createdAt) {
            return false;
        }

        if (is_string($createdAt) || is_numeric($createdAt)) {
            $createdAt = Carbon::parse($createdAt);
        }

        $measurementsInDay = $this->measurementsInDay($createdAt);

        $anyUnchecked = false;
        foreach ($measurementsInDay as $measurement) {
            if (
                $measurement['triggered_safety_alarm_max']
                || $measurement['triggered_safety_alarm_min']
                || $measurement['triggered_therapeutic_alarm_max']
                || $measurement['triggered_therapeutic_alarm_max']
            ) {
                if ($measurement['checked'] == false) {
                    $anyUnchecked = true;
                }
            }
        }

        return $anyUnchecked;
    }

    public function isAnyMeasurementUnchecked()
    {

        $summary = $this->measurementsSummary();

        $anyUnchecked = false;
        foreach ($summary as $day) {
            foreach ($day as $measurement) {
                if (
                    $measurement['triggered_safety_alarm_max']
                    || $measurement['triggered_safety_alarm_min']
                    || $measurement['triggered_therapeutic_alarm_max']
                    || $measurement['triggered_therapeutic_alarm_max']
                ) {
                    if ($measurement['checked'] == false) {
                        $anyUnchecked = true;
                    }
                }
            }
        }

        return $anyUnchecked;
    }

    public function setMeasurementChecked(int $measurementId, bool $checked)
    {
        $measurement = Measurement::where('id', $measurementId)->first();
        $measurement->checked = $checked;
        $measurement->save();

        return Measurement::where('id', $measurementId)->first()->checked == $checked;
    }

    public function setMeasurementsInDayChecked(object $createdAt, bool $checked)
    {
        $allChecked = true;
        $checkedOne = false;

        $measurementsInDay = $this->measurementsInDay($createdAt);
        foreach ($measurementsInDay as $measurement) {
            if (!is_array($measurement)) {
                $checkedOne = $this->setMeasurementChecked($measurement->id, $checked);
            } else {
                if (array_key_exists('id', $measurement)) {
                    $checkedOne = $this->setMeasurementChecked($measurement['id'], $checked);
                }
            }

            $allChecked = $checkedOne != false;
        }

        return $allChecked;
    }

    public function setAllMeasurementsChecked(bool $checked)
    {
        $allChecked = true;
        $checkedOne = false;

        $alarms = $this->measurementsSummary();
        foreach ($alarms as $day) {
            foreach ($day as $measurement) {
                if ($measurement['checked'] != $checked) {

                    if (!is_array($measurement)) {
                        if ($measurement->measurementId) {
                            $checkedOne = $this->setMeasurementChecked($measurement->measurementId, $checked);
                        }
                    } else {
                        if (array_key_exists('measurementId', $measurement)) {
                            if ($measurement['measurementId']) {
                                $checkedOne = $this->setMeasurementChecked($measurement['measurementId'], $checked);
                            }
                        }
                    }
                }
            }

            $allChecked = $checkedOne != false;
        }

        return $allChecked;
    }

    public function hasCondition(int $id)
    {
        $conditions = $this->conditions;
        foreach ($conditions as $condition) {
            if ($condition->id == $id) {
                return true;
            }
        }
        return false;
    }

    public function purgeConditions()
    {
        $conditions = $this->conditions;
        foreach ($conditions as $condition) {
            $this->conditions()->detach($condition->id);
        }
    }

    public function hasDrug(Drug $drugToCheck)
    {
        $drugs = $this->drugs;
        foreach ($drugs as $drug) {
            if ($drug->id == $drugToCheck->id) {
                return true;
            }
        }
        return false;
    }

    public function purgeDrugs()
    {
        $drugs = $this->drugs;
        foreach ($drugs as $drug) {
            $this->drugs()->detach($drug->id);
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Minwork\Helper\Arr;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'mobile',
        'sex',
        'age',
        'height',
        'weight',
        'is_coordinator',
        'coordinator_id',
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

    public function measurementsSummary()
    {
        $parameters = $this->parameters->toArray();
        $measurementsGrouped = $this->measurementsByDay();

        return array_map(function ($measurementsPerDay) use ($parameters) {
            $values = array_map(function ($parameter) use ($measurementsPerDay) {
                $value = null;
                $alarm = false;

                foreach ($measurementsPerDay as $measurement) {
                    if ($measurement['parameter_id'] == $parameter['id']) {
                        $value = $measurement['value'];

                        $alarm = $measurement['triggered_therapeutic_alarm_min'] || $measurement['triggered_therapeutic_alarm_max'] ||  $measurement['triggered_safety_alarm_min'] || $measurement['triggered_safety_alarm_max'];

                        if ($alarm) {
                            $alarmGeneral = true;
                        }
                    }
                }

                return ['parameter' => $parameter['name'], 'value' => $value, 'unit' => $parameter['unit'], 'alarm' => $alarm, 'date' => $measurement['created_at']];
            }, $parameters);

            $conditions = Arr::map(['swellings' => 'Swellings', 'exercise_tolerance' => 'Exercise Tolerance', 'dyspnoea' => 'Nocturnal Dyspnoea'], function ($key, $name) use ($measurementsPerDay) {
                $avg = null;
                $avgMapped = '';
                // TODO
                $alarm = false;

                if (count($measurementsPerDay) > 0) {
                    $avg = 0;
                    foreach ($measurementsPerDay as $measurement) {
                        $avg = $avg + $measurement[$key] ?? 0;
                    }
                    $avg = $avg / count($measurementsPerDay);

                    $avgMapped = $this->mapConditions(ceil($avg));
                }

                return ['name' => $name, 'value' => $avgMapped, 'alarm' => $alarm];
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

    public function parameters()
    {
        return $this->belongsToMany(Parameter::class, 'user_parameters', 'user_id', 'parameter_id')->withPivot('threshold_safety_min', 'threshold_safety_max', 'threshold_therapeutic_min', 'threshold_therapeutic_max');
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
        return $this->belongsToMany(Drug::class, 'user_drugs');
    }

    public function thresholds()
    {
        $parametersGlobal =  Parameter::all();
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
}

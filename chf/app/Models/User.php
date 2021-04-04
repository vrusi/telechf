<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
                'therapeuticMin' => $therapeuticMin];
        }
        
        return $thresholds;
    }
}

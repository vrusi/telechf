<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parameter_id',
        'value',
        'swellings',
        'exercise_tolerance',
        'dyspnoea',
        'triggered_safety_alarm_min',
        'triggered_safety_alarm_max',
        'triggered_therapeutic_alarm_min',
        'triggered_therapeutic_alarm_max',
    ];
}

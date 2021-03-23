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
        'triggered_safety_alarm',
        'triggered_therapeutic_alarm',
    ];
}

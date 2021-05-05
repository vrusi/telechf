<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Drug extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'dosage_volume',
        'dosage_unit',
        'dosage_times',
        'dosage_span',
        'user_id',
        'id',
        'name',
    ];
}

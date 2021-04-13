<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'measurement_id',
        'author_id',
        'value',
    ];

    public function measurement()
    {
        return $this->belongsTo(Measurement::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}

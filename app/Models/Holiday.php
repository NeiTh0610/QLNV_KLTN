<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'end_date',
        'is_recurring',
        'compensate_to',
    ];

    protected $casts = [
        'date' => 'date',
        'end_date' => 'date',
        'compensate_to' => 'date',
        'is_recurring' => 'boolean',
    ];
}


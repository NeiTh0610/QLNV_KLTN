<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalaryGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'salary_type',
        'base_salary',
        'allowance_percent',
        'description',
        'is_active',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'allowance_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function profiles(): HasMany
    {
        return $this->hasMany(UserProfile::class);
    }
}


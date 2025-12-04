<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'position',
        'salary_grade_id',
        'base_salary_override',
        'id_number',
        'date_of_birth',
        'gender',
        'address',
        'personal_email',
        'emergency_contact_name',
        'emergency_contact_phone',
        'education_level',
        'major',
        'university',
        'years_of_experience',
        'skills',
        'certifications',
        'previous_work',
    ];

    protected $casts = [
        'base_salary_override' => 'decimal:2',
        'date_of_birth' => 'date',
        'years_of_experience' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salaryGrade(): BelongsTo
    {
        return $this->belongsTo(SalaryGrade::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'period_start',
        'period_end',
        'basic_salary',
        'working_days',
        'hours_per_day',
        'ot_hours',
        'late_minutes',
        'late_count_under_30',
        'late_count_half_day',
        'leave_minutes',
        'early_leave_count_under_30',
        'early_leave_count_half_day',
        'deductions',
        'allowances',
        'gross_salary',
        'social_insurance',
        'health_insurance',
        'unemployment_insurance',
        'personal_income_tax',
        'net_pay',
        'status',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'basic_salary' => 'decimal:2',
        'working_days' => 'decimal:2',
        'hours_per_day' => 'decimal:2',
        'ot_hours' => 'decimal:2',
        'deductions' => 'decimal:2',
        'allowances' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'social_insurance' => 'decimal:2',
        'health_insurance' => 'decimal:2',
        'unemployment_insurance' => 'decimal:2',
        'personal_income_tax' => 'decimal:2',
        'net_pay' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


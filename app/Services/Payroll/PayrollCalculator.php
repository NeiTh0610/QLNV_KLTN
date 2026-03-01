<?php

namespace App\Services\Payroll;

use App\Models\Attendance;
use App\Models\OvertimeRequest;
use App\Models\PayrollRecord;
use App\Models\User;
use App\Support\Settings;
use Carbon\Carbon;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Collection;

class PayrollCalculator
{
    public function generate(Carbon $periodStart, Carbon $periodEnd): Collection
    {
        $users = $this->eligibleUsers();
        $attendanceMap = $this->attendanceSummary($periodStart, $periodEnd);
        $records = collect();

        foreach ($users as $user) {
            $profile = $user->profile;
            $grade = $profile?->salaryGrade;
            if (! $grade) {
                continue;
            }

            $baseSalary = (float) ($profile->base_salary_override ?? $grade->base_salary);
            $salaryType = $grade->salary_type;
            $hoursPerDay = null;

            $attendanceData = $attendanceMap->get($user->id, [
                'working_days' => 0,
                'worked_minutes' => 0,
                'late_minutes' => 0,
                'late_count_under_30' => 0,
                'late_count_half_day' => 0,
                'early_leave_minutes' => 0,
                'early_leave_count_under_30' => 0,
                'early_leave_count_half_day' => 0,
                'overtime_minutes' => 0,
            ]);

            if ($salaryType === 'hourly') {
                $workingDays = $attendanceData['working_days'];
                $totalWorkedMinutes = $attendanceData['worked_minutes'];
                if ($workingDays > 0 && $totalWorkedMinutes > 0) {
                    $hoursPerDay = ($totalWorkedMinutes / 60) / $workingDays;
                    $actualBaseSalary = round($hoursPerDay * $workingDays * $baseSalary, 0);
                } else {
                    $hoursPerDay = $workingDays > 0 ? 8 : 0;
                    $actualBaseSalary = $workingDays > 0 ? round($hoursPerDay * $workingDays * $baseSalary, 0) : 0;
                }
                $allowances = 0;
            } else {
                $allowances = round($baseSalary * ((float) $grade->allowance_percent) / 100, 2);
                $standardWorkingDays = $this->countWeekdays($periodStart, $periodEnd);
                $actualWorkingDays = $attendanceData['working_days'];
                if ($actualWorkingDays > 0 && $standardWorkingDays > 0) {
                    $salaryPerDay = $baseSalary / $standardWorkingDays;
                    $actualBaseSalary = round(($salaryPerDay * $actualWorkingDays) / 1000) * 1000;
                } else {
                    $actualBaseSalary = 0;
                }
            }

            $overtimeBonus = 0;
            if ($salaryType === 'monthly') {
                $standardWorkingDays = $this->countWeekdays($periodStart, $periodEnd);
                $hourlyRate = $standardWorkingDays > 0 ? $baseSalary / ($standardWorkingDays * 8) : 0;
                $overtimeBonus = $hourlyRate > 0 && $attendanceData['overtime_minutes'] > 0
                    ? round(($attendanceData['overtime_minutes'] / 60) * $hourlyRate * 1.5, 0)
                    : 0;
            }

            $latePenalty = ($attendanceData['late_count_under_30'] * 50000) + ($attendanceData['late_count_half_day'] * 500000);
            $earlyLeavePenalty = ($attendanceData['early_leave_count_under_30'] * 50000) + ($attendanceData['early_leave_count_half_day'] * 500000);
            $grossSalary = $actualBaseSalary + $allowances + $overtimeBonus;
            $totalDeductions = $latePenalty + $earlyLeavePenalty;

            if ($salaryType === 'hourly') {
                $insuranceDetails = ['bhxh' => 0, 'bhyt' => 0, 'bhtn' => 0, 'total' => 0];
                $personalIncomeTax = 0;
            } else {
                $insuranceDetails = $this->calculateInsurance($baseSalary);
                $personalIncomeTax = $this->calculatePersonalIncomeTax(
                    $this->calculateTaxableIncome($grossSalary, $insuranceDetails['total'])
                );
            }

            $netPay = max(0, $grossSalary - $insuranceDetails['total'] - $personalIncomeTax - $totalDeductions);

            $periodStartStr = $periodStart->format('Y-m-d');
            $periodEndStr = $periodEnd->format('Y-m-d');
            $attributes = ['user_id' => $user->id, 'period_start' => $periodStartStr, 'period_end' => $periodEndStr];
            $values = [
                'basic_salary' => $actualBaseSalary,
                'working_days' => $attendanceData['working_days'],
                'hours_per_day' => $salaryType === 'hourly' && $hoursPerDay !== null ? round($hoursPerDay, 2) : null,
                'ot_hours' => round($attendanceData['overtime_minutes'] / 60, 2),
                'late_minutes' => $attendanceData['late_minutes'],
                'late_count_under_30' => $attendanceData['late_count_under_30'],
                'late_count_half_day' => $attendanceData['late_count_half_day'],
                'leave_minutes' => $attendanceData['early_leave_minutes'],
                'early_leave_count_under_30' => $attendanceData['early_leave_count_under_30'],
                'early_leave_count_half_day' => $attendanceData['early_leave_count_half_day'],
                'deductions' => $totalDeductions,
                'allowances' => $allowances,
                'gross_salary' => $grossSalary,
                'social_insurance' => $insuranceDetails['bhxh'],
                'health_insurance' => $insuranceDetails['bhyt'],
                'unemployment_insurance' => $insuranceDetails['bhtn'],
                'personal_income_tax' => $personalIncomeTax,
                'net_pay' => $netPay,
                'status' => 'draft',
            ];

            try {
                $record = PayrollRecord::updateOrCreate($attributes, $values);
            } catch (UniqueConstraintViolationException $e) {
                $record = PayrollRecord::where('user_id', $user->id)
                    ->where('period_start', $periodStartStr)
                    ->where('period_end', $periodEndStr)
                    ->firstOrFail();
                $record->update($values);
            }
            $records->push($record);
        }

        return $records;
    }

    protected function eligibleUsers(): Collection
    {
        return User::query()
            ->where('status', 'active')
            ->with(['profile.salaryGrade'])
            ->whereHas('profile', fn ($q) => $q->whereHas('salaryGrade', fn ($g) => $g->whereIn('salary_type', ['monthly', 'hourly'])))
            ->get();
    }

    protected function countWeekdays(Carbon $start, Carbon $end): int
    {
        $n = 0;
        $current = $start->copy();
        while ($current->lte($end)) {
            if ($current->isWeekday()) {
                $n++;
            }
            $current->addDay();
        }
        return $n;
    }

    protected function attendanceSummary(Carbon $start, Carbon $end): Collection
    {
        $workingStart = Settings::get('attendance.working_hours.start', '08:00');
        $workingEnd = Settings::get('attendance.working_hours.end', '17:00');
        $startTime = Carbon::parse($workingStart);
        $endTime = Carbon::parse($workingEnd);
        $standardWorkingHours = $endTime->diffInHours($startTime);

        return Attendance::query()
            ->whereBetween('work_date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->groupBy('user_id')
            ->map(function ($attendances) use ($startTime, $endTime, $standardWorkingHours) {
                $workingDays = 0;
                $workedMinutes = 0;
                $lateMinutes = 0;
                $lateCountUnder30 = 0;
                $lateCountHalfDay = 0;
                $earlyLeaveMinutes = 0;
                $earlyLeaveCountUnder30 = 0;
                $earlyLeaveCountHalfDay = 0;
                $overtimeMinutes = 0;

                foreach ($attendances as $attendance) {
                    if (! $attendance->check_in_at || ! $attendance->check_out_at) {
                        continue;
                    }
                    $workingDays++;
                    $checkIn = Carbon::parse($attendance->check_in_at);
                    $checkOut = Carbon::parse($attendance->check_out_at);
                    $actualWorkedMinutes = max(0, $checkOut->diffInMinutes($checkIn));
                    $workedMinutes += $actualWorkedMinutes;

                    $expectedStart = $checkIn->clone()->setTimeFrom($startTime);
                    if ($checkIn->greaterThan($expectedStart)) {
                        $lateMin = $expectedStart->diffInMinutes($checkIn);
                        $lateMinutes += $lateMin;
                        if ($lateMin < 30) {
                            $lateCountUnder30++;
                        } else {
                            $lateCountHalfDay++;
                        }
                    }

                    $expectedEnd = $checkOut->clone()->setTimeFrom($endTime);
                    if ($checkOut->lessThan($expectedEnd)) {
                        $earlyMin = $checkOut->diffInMinutes($expectedEnd);
                        $earlyLeaveMinutes += $earlyMin;
                        if ($earlyMin < 30) {
                            $earlyLeaveCountUnder30++;
                        } else {
                            $earlyLeaveCountHalfDay++;
                        }
                    }

                    $standardMinutes = $standardWorkingHours * 60;
                    if ($actualWorkedMinutes > $standardMinutes) {
                        $otRequest = OvertimeRequest::where('user_id', $attendance->user_id)
                            ->where('date', $attendance->work_date)
                            ->where('status', 'approved')
                            ->first();
                        if ($otRequest) {
                            $regOtMinutes = $otRequest->hours * 60;
                            $actualOtMinutes = $actualWorkedMinutes - $standardMinutes;
                            $overtimeMinutes += min($regOtMinutes, $actualOtMinutes);
                        }
                    }
                }

                return [
                    'working_days' => $workingDays,
                    'worked_minutes' => $workedMinutes,
                    'late_minutes' => $lateMinutes,
                    'late_count_under_30' => $lateCountUnder30,
                    'late_count_half_day' => $lateCountHalfDay,
                    'early_leave_minutes' => $earlyLeaveMinutes,
                    'early_leave_count_under_30' => $earlyLeaveCountUnder30,
                    'early_leave_count_half_day' => $earlyLeaveCountHalfDay,
                    'overtime_minutes' => $overtimeMinutes,
                ];
            });
    }

    protected function calculateInsurance(float $baseSalary): array
    {
        $cap = (float) Settings::get('payroll.insurance_base_cap', 36000000);
        $base = min($baseSalary, $cap);
        $bhxh = round($base * (float) Settings::get('payroll.bhxh_rate_employee', 0.08), 0);
        $bhyt = round($base * (float) Settings::get('payroll.bhyt_rate_employee', 0.015), 0);
        $bhtn = round($base * (float) Settings::get('payroll.bhtn_rate_employee', 0.01), 0);
        return ['bhxh' => $bhxh, 'bhyt' => $bhyt, 'bhtn' => $bhtn, 'total' => $bhxh + $bhyt + $bhtn];
    }

    protected function calculateTaxableIncome(float $grossSalary, float $insuranceTotal): float
    {
        $deduction = (float) Settings::get('payroll.personal_deduction', 11000000);
        return max(0, $grossSalary - $insuranceTotal - $deduction);
    }

    protected function calculatePersonalIncomeTax(float $taxable): float
    {
        if ($taxable <= 0) {
            return 0;
        }
        $brackets = [
            ['limit' => 5000000, 'rate' => 0.05],
            ['limit' => 10000000, 'rate' => 0.10],
            ['limit' => 18000000, 'rate' => 0.15],
            ['limit' => 32000000, 'rate' => 0.20],
            ['limit' => 52000000, 'rate' => 0.25],
            ['limit' => 80000000, 'rate' => 0.30],
            ['limit' => PHP_FLOAT_MAX, 'rate' => 0.35],
        ];
        $tax = 0;
        $prev = 0;
        $rem = $taxable;
        foreach ($brackets as $b) {
            $slice = min($rem, $b['limit'] - $prev);
            if ($slice > 0) {
                $tax += $slice * $b['rate'];
                $rem -= $slice;
            }
            $prev = $b['limit'];
            if ($rem <= 0) {
                break;
            }
        }
        return round($tax, 0);
    }
}

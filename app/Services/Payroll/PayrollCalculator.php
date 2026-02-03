<?php

namespace App\Services\Payroll;

use App\Models\Attendance;
use App\Models\OvertimeRequest;
use App\Models\PayrollRecord;
use App\Models\User;
use App\Support\Settings;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PayrollCalculator
{
    /**
     * Generate payroll records for active staff (both full-time and part-time).
     */
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
            $salaryType = $grade->salary_type; // 'monthly' or 'hourly'
            $hoursPerDay = null; // Khởi tạo biến

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

            // Tính lương cơ bản
            if ($salaryType === 'hourly') {
                // Part-time: Tính theo công thức: Số giờ đi làm 1 ngày × Số ngày × Lương 1 giờ
                $workingDays = $attendanceData['working_days']; // Số ngày đi làm
                $totalWorkedMinutes = $attendanceData['worked_minutes']; // Tổng số phút làm việc
                
                if ($workingDays > 0 && $totalWorkedMinutes > 0) {
                    // Tính số giờ làm việc trung bình mỗi ngày
                    $hoursPerDay = ($totalWorkedMinutes / 60) / $workingDays;
                    // Tính lương: Số giờ/ngày × Số ngày × Lương/giờ
                    $actualBaseSalary = round($hoursPerDay * $workingDays * $baseSalary, 0);
                } else {
                    // Nếu không có dữ liệu giờ làm việc, giả sử 8 giờ/ngày (mặc định)
                    if ($workingDays > 0 && $totalWorkedMinutes == 0) {
                        $hoursPerDay = 8; // Mặc định 8 giờ/ngày nếu không có dữ liệu
                        $actualBaseSalary = round($hoursPerDay * $workingDays * $baseSalary, 0);
                    } else {
                        $actualBaseSalary = 0;
                        $hoursPerDay = 0;
                    }
                }
                $allowances = 0; // Part-time thường không có phụ cấp
            } else {
                // Full-time: Tính theo ngày làm việc
                $allowances = round($baseSalary * ((float) $grade->allowance_percent) / 100, 2);
                
                // Tính số ngày làm việc chuẩn trong kỳ (T2-T6, trừ ngày lễ)
                $standardWorkingDays = 0;
                $current = $periodStart->copy();
                while ($current->lte($periodEnd)) {
                    if ($current->isWeekday()) {
                        $standardWorkingDays++;
                    }
                    $current->addDay();
                }
                
                $actualWorkingDays = $attendanceData['working_days'];
                
                // Tính lương cơ bản theo tỷ lệ ngày làm việc thực tế
                if ($actualWorkingDays > 0 && $standardWorkingDays > 0) {
                    // Tính lương theo tỷ lệ: (Lương tháng / Số ngày chuẩn) × Số ngày đi làm
                    $salaryPerDay = $baseSalary / $standardWorkingDays;
                    $calculatedSalary = $salaryPerDay * $actualWorkingDays;
                    // Làm tròn đến hàng nghìn (1.000đ)
                    $actualBaseSalary = round($calculatedSalary / 1000) * 1000;
                } else {
                    // Nếu không đi làm ngày nào, lương = 0
                    $actualBaseSalary = 0;
                }
            }
            
            // Tính lương giờ để tính OT và phạt (chỉ cho full-time)
            $overtimeBonus = 0;
            if ($salaryType === 'monthly') {
                // Full-time: Tính lương giờ từ lương tháng
                $standardWorkingDays = 0;
                $current = $periodStart->copy();
                while ($current->lte($periodEnd)) {
                    if ($current->isWeekday()) {
                        $standardWorkingDays++;
                    }
                    $current->addDay();
                }
                $hourlyRate = $standardWorkingDays > 0 ? $baseSalary / ($standardWorkingDays * 8) : 0;
                
                // Tính thưởng làm thêm giờ (1.5x lương giờ) - chỉ cho full-time
                $overtimeBonus = $hourlyRate > 0 && $attendanceData['overtime_minutes'] > 0 
                    ? round(($attendanceData['overtime_minutes'] / 60) * $hourlyRate * 1.5, 0) 
                    : 0;
            }
            // Part-time: Không có làm thêm giờ
            
            // Tính phạt đi muộn: < 30p = 50.000đ, >= 30p (nửa ngày) = 500.000đ
            $latePenalty = ($attendanceData['late_count_under_30'] * 50000) + ($attendanceData['late_count_half_day'] * 500000);
            
            // Tính phạt về sớm: < 30p = 50.000đ, >= 30p (nửa ngày) = 500.000đ
            $earlyLeavePenalty = ($attendanceData['early_leave_count_under_30'] * 50000) + ($attendanceData['early_leave_count_half_day'] * 500000);

            // Tổng lương trước thuế
            $grossSalary = $actualBaseSalary + $allowances + $overtimeBonus;
            $totalDeductions = $latePenalty + $earlyLeavePenalty;
            
            // Part-time: Không đóng bảo hiểm và thuế
            if ($salaryType === 'hourly') {
                // Part-time: Không trừ bảo hiểm và thuế
                $insuranceDetails = [
                    'bhxh' => 0,
                    'bhyt' => 0,
                    'bhtn' => 0,
                    'total' => 0,
                ];
                $personalIncomeTax = 0;
            } else {
                // Full-time: Tính bảo hiểm và thuế
                // Bảo hiểm tính trên lương cơ bản (baseSalary), không tính trên phụ cấp và thưởng
                $insuranceDetails = $this->calculateInsurance($baseSalary);
                $personalIncomeTax = $this->calculatePersonalIncomeTax(
                    $this->calculateTaxableIncome(
                        $grossSalary,
                        $insuranceDetails['total'],
                    )
                );
            }

            $netPay = max(
                0,
                $grossSalary
                - $insuranceDetails['total']
                - $personalIncomeTax
                - $totalDeductions
            );

            $record = PayrollRecord::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'period_start' => $periodStart->toDateString(),
                    'period_end' => $periodEnd->toDateString(),
                ],
                [
                    'basic_salary' => $actualBaseSalary,
                    'working_days' => $attendanceData['working_days'], // Số ngày đi làm
                    'hours_per_day' => $salaryType === 'hourly' ? ($hoursPerDay !== null ? round($hoursPerDay, 2) : null) : null, // Part-time: số giờ làm việc trung bình mỗi ngày
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
                ]
            );

            $records->push($record);
        }

        return $records;
    }

    /**
     * Fetch all active users with salary grades (both monthly and hourly).
     *
     * @return \Illuminate\Support\Collection<int, \App\Models\User>
     */
    protected function eligibleUsers(): Collection
    {
        return User::query()
            ->where('status', 'active')
            ->with(['profile.salaryGrade'])
            ->whereHas('profile', function ($query) {
                $query->whereHas('salaryGrade', function ($gradeQuery) {
                    $gradeQuery->whereIn('salary_type', ['monthly', 'hourly']);
                });
            })
            ->get();
    }

    /**
     * Summaries attendance metrics for users within the period.
     */
    protected function attendanceSummary(Carbon $start, Carbon $end): Collection
    {
        $workingStart = Settings::get('attendance.working_hours.start', '08:00');
        $workingEnd = Settings::get('attendance.working_hours.end', '17:00');

        $startTime = Carbon::parse($workingStart);
        $endTime = Carbon::parse($workingEnd);
        $standardWorkingHours = $endTime->diffInHours($startTime); // VD: 9 giờ (8h-17h)

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

                    // Tính đi muộn: phân loại < 30p hoặc >= 30p (nửa ngày)
                    $expectedStart = $checkIn->clone()->setTimeFrom($startTime);
                    if ($checkIn->greaterThan($expectedStart)) {
                        $lateMinutesForDay = $expectedStart->diffInMinutes($checkIn);
                        $lateMinutes += $lateMinutesForDay;
                        
                        // Phân loại: < 30 phút hoặc >= 30 phút (nửa ngày)
                        if ($lateMinutesForDay < 30) {
                            $lateCountUnder30++;
                        } else {
                            $lateCountHalfDay++;
                        }
                    }

                    // Tính về sớm: phân loại < 30p hoặc >= 30p (nửa ngày)
                    $expectedEnd = $checkOut->clone()->setTimeFrom($endTime);
                    if ($checkOut->lessThan($expectedEnd)) {
                        $earlyLeaveMinutesForDay = $checkOut->diffInMinutes($expectedEnd);
                        $earlyLeaveMinutes += $earlyLeaveMinutesForDay;
                        
                        // Phân loại: < 30 phút hoặc >= 30 phút (nửa ngày)
                        if ($earlyLeaveMinutesForDay < 30) {
                            $earlyLeaveCountUnder30++;
                        } else {
                            $earlyLeaveCountHalfDay++;
                        }
                    }

                    // Tính làm thêm giờ (chỉ tính nếu đã đăng ký và được duyệt)
                    $standardMinutes = $standardWorkingHours * 60;
                    if ($actualWorkedMinutes > $standardMinutes) {
                        // Kiểm tra xem có đăng ký làm thêm giờ cho ngày này không
                        $overtimeRequest = OvertimeRequest::where('user_id', $attendance->user_id)
                            ->where('date', $attendance->work_date)
                            ->where('status', 'approved')
                            ->first();
                        
                        if ($overtimeRequest) {
                            // Chỉ tính số giờ đã đăng ký và được duyệt
                            $registeredOvertimeMinutes = $overtimeRequest->hours * 60;
                            // Tính số phút làm thêm thực tế
                            $actualOvertimeMinutes = $actualWorkedMinutes - $standardMinutes;
                            // Lấy số phút nhỏ hơn giữa đăng ký và thực tế
                            $overtimeMinutes += min($registeredOvertimeMinutes, $actualOvertimeMinutes);
                        }
                        // Nếu không có đăng ký hoặc chưa được duyệt, không tính làm thêm giờ
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

    /**
     * Calculate employee-side insurance contributions.
     *
     * @return array{bhxh: float, bhyt: float, bhtn: float, total: float}
     */
    protected function calculateInsurance(float $baseSalary): array
    {
        $cap = (float) Settings::get('payroll.insurance_base_cap', 36000000);
        $insuranceBase = min($baseSalary, $cap);

        $bhxhRate = (float) Settings::get('payroll.bhxh_rate_employee', 0.08);
        $bhytRate = (float) Settings::get('payroll.bhyt_rate_employee', 0.015);
        $bhtnRate = (float) Settings::get('payroll.bhtn_rate_employee', 0.01);

        $bhxh = round($insuranceBase * $bhxhRate, 0);
        $bhyt = round($insuranceBase * $bhytRate, 0);
        $bhtn = round($insuranceBase * $bhtnRate, 0);

        return [
            'bhxh' => $bhxh,
            'bhyt' => $bhyt,
            'bhtn' => $bhtn,
            'total' => $bhxh + $bhyt + $bhtn,
        ];
    }

    /**
     * Determine taxable income.
     */
    protected function calculateTaxableIncome(float $grossSalary, float $insuranceTotal): float
    {
        $personalDeduction = (float) Settings::get('payroll.personal_deduction', 11000000);

        $taxable = $grossSalary - $insuranceTotal - $personalDeduction;

        return max(0, $taxable);
    }

    /**
     * Progressive PIT calculation (Vietnam).
     */
    protected function calculatePersonalIncomeTax(float $taxableIncome): float
    {
        if ($taxableIncome <= 0) {
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
        $previousLimit = 0;
        $remaining = $taxableIncome;

        foreach ($brackets as $bracket) {
            $applicable = min($remaining, $bracket['limit'] - $previousLimit);

            if ($applicable > 0) {
                $tax += $applicable * $bracket['rate'];
                $remaining -= $applicable;
            }

            $previousLimit = $bracket['limit'];

            if ($remaining <= 0) {
                break;
            }
        }

        return round($tax, 0);
    }
}


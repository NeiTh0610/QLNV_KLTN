<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\PayrollRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PayrollSampleSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['employee', 'part_time']);
        })->get();

        $months = [
            '2025-11',
            '2025-12',
        ];

        foreach ($months as $month) {
            $start = Carbon::parse($month . '-01')->startOfDay();
            $end = $start->copy()->endOfMonth();

            foreach ($users as $user) {
                // Create attendances for every weekday in the month
                $current = $start->copy();
                while ($current->lte($end)) {
                    if ($current->isWeekday()) {
                        // 80% present
                        if (rand(1, 100) <= 80) {
                            $workDate = $current->format('Y-m-d');

                            $checkInHour = rand(7, 8);
                            $checkInMinute = rand(0, 59);
                            $checkIn = Carbon::parse("{$workDate} {$checkInHour}:{$checkInMinute}:00");

                            $checkOutHour = rand(16, 19);
                            $checkOutMinute = rand(0, 59);
                            $checkOut = Carbon::parse("{$workDate} {$checkOutHour}:{$checkOutMinute}:00");

                            $standardStart = Carbon::parse("{$workDate} 08:00:00");
                            $standardEnd = Carbon::parse("{$workDate} 17:00:00");
                            $status = 'on_time';
                            if ($checkIn->gt($standardStart->copy()->addMinutes(15))) {
                                $status = 'late';
                            } elseif ($checkOut->lt($standardEnd->copy()->subMinutes(15))) {
                                $status = 'early_leave';
                            }

                            Attendance::updateOrCreate([
                                'user_id' => $user->id,
                                'work_date' => $workDate,
                            ], [
                                'check_in_at' => $checkIn,
                                'check_in_method' => 'manual',
                                'check_in_ip' => '127.0.0.1',
                                'check_out_at' => $checkOut,
                                'check_out_method' => 'manual',
                                'check_out_ip' => '127.0.0.1',
                                'status' => $status,
                            ]);
                        }
                    }

                    $current->addDay();
                }

                // Compute payroll summary for the month
                $workingDays = 0;
                $temp = $start->copy();
                while ($temp->lte($end)) {
                    if ($temp->isWeekday()) {
                        $workingDays++;
                    }
                    $temp->addDay();
                }

                $presentCount = Attendance::where('user_id', $user->id)
                    ->whereBetween('work_date', [$start->toDateString(), $end->toDateString()])
                    ->whereNotNull('check_in_at')
                    ->whereNotNull('check_out_at')
                    ->count();

                $attendances = Attendance::where('user_id', $user->id)
                    ->whereBetween('work_date', [$start->toDateString(), $end->toDateString()])
                    ->whereNotNull('check_in_at')
                    ->whereNotNull('check_out_at')
                    ->get();

                $otHours = 0.0;
                foreach ($attendances as $a) {
                    $standardEnd = Carbon::parse($a->work_date->format('Y-m-d') . ' 17:00:00');
                    $diff = max(0, $a->check_out_at->diffInMinutes($standardEnd));
                    $otHours += $diff / 60.0;
                }

                $profile = $user->profile;
                $baseSalary = 0.0;
                if ($profile) {
                    $baseSalary = $profile->base_salary_override ?? ($profile->salaryGrade?->base_salary ?? 0);
                }

                // Fallback basic salary if none
                if (!$baseSalary) {
                    $baseSalary = 3000000; // 3,000,000 VND default
                }

                $proRated = $workingDays > 0 ? ($presentCount / $workingDays) : 0;
                $basic_pay = round($baseSalary * $proRated, 2);

                $hourlyRate = $workingDays > 0 ? ($baseSalary / ($workingDays * 8)) : 0;
                $ot_pay = round($otHours * $hourlyRate * 1.5, 2);

                $gross = round($basic_pay + $ot_pay, 2);

                $social = round($gross * 0.08, 2);
                $health = round($gross * 0.015, 2);
                $unemployment = round($gross * 0.01, 2);
                $tax = round($gross * 0.1, 2);

                $totalDeductions = $social + $health + $unemployment + $tax;
                $net = round($gross - $totalDeductions, 2);

                PayrollRecord::updateOrCreate([
                    'user_id' => $user->id,
                    'period_start' => $start->toDateString(),
                    'period_end' => $end->toDateString(),
                ], [
                    'basic_salary' => $baseSalary,
                    'working_days' => $workingDays,
                    'hours_per_day' => 8,
                    'ot_hours' => round($otHours, 2),
                    'late_minutes' => 0,
                    'late_count_under_30' => 0,
                    'late_count_half_day' => 0,
                    'leave_minutes' => 0,
                    'early_leave_count_under_30' => 0,
                    'early_leave_count_half_day' => 0,
                    'deductions' => $totalDeductions,
                    'allowances' => 0,
                    'gross_salary' => $gross,
                    'social_insurance' => $social,
                    'health_insurance' => $health,
                    'unemployment_insurance' => $unemployment,
                    'personal_income_tax' => $tax,
                    'net_pay' => $net,
                    'status' => 'draft',
                ]);
            }
        }
    }
}

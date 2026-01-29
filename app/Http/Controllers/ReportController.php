<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'month');
        $date = $request->get('date', now()->format('Y-m'));
        
        if ($period === 'day') {
            $startDate = Carbon::parse($date);
            $endDate = $startDate->copy();
        } elseif ($period === 'week') {
            $startDate = Carbon::parse($date)->startOfWeek();
            $endDate = $startDate->copy()->endOfWeek();
        } else {
            $startDate = Carbon::parse($date)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
        }

        $reports = $this->generateReport($startDate, $endDate, $request->get('department_id'));
        $departmentReports = $this->generateDepartmentReport($startDate, $endDate, $request->get('department_id'));

        $departments = \App\Models\Department::all();

        return view('reports.index', compact('reports', 'departmentReports', 'period', 'date', 'departments', 'startDate', 'endDate'));
    }

    private function generateReport($startDate, $endDate, $departmentId = null)
    {
        $query = Attendance::with(['user.profile.department'])
            ->whereBetween('work_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        if ($departmentId) {
            $query->whereHas('user.profile', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $attendances = $query->get();

        return $attendances->groupBy('user_id')->map(function($userAttendances) {
            $user = $userAttendances->first()->user;
            $totalDays = $userAttendances->count();
            $onTimeDays = $userAttendances->where('status', 'on_time')->count();
            $lateDays = $userAttendances->where('status', 'late')->count();
            $earlyLeaveDays = $userAttendances->where('status', 'early_leave')->count();
            $absentDays = $userAttendances->where('status', 'absent')->count();

            return [
                'user' => $user,
                'total_days' => $totalDays,
                'on_time' => $onTimeDays,
                'late' => $lateDays,
                'early_leave' => $earlyLeaveDays,
                'absent' => $absentDays,
            ];
        })->values();
    }

    private function generateDepartmentReport($startDate, $endDate, $departmentId = null)
    {
        $totalDaysInPeriod = $startDate->diffInDays($endDate) + 1;
        
        $query = Attendance::with(['user.profile.department'])
            ->whereBetween('work_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        if ($departmentId) {
            $query->whereHas('user.profile', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $attendances = $query->get();

        // Nhóm theo phòng ban
        $departmentStats = $attendances->groupBy(function($attendance) {
            return $attendance->user->profile->department_id ?? 'no_department';
        })->map(function($deptAttendances, $deptId) use ($totalDaysInPeriod) {
            $department = $deptAttendances->first()->user->profile->department ?? null;
            
            // Lấy danh sách nhân viên duy nhất trong phòng ban
            $uniqueUsers = $deptAttendances->groupBy('user_id');
            $totalEmployees = $uniqueUsers->count();
            
            // Tính tổng các chỉ số
            $totalDays = $deptAttendances->count();
            $totalOnTime = $deptAttendances->where('status', 'on_time')->count();
            $totalLate = $deptAttendances->where('status', 'late')->count();
            $totalEarlyLeave = $deptAttendances->where('status', 'early_leave')->count();
            $totalAbsent = $deptAttendances->where('status', 'absent')->count();
            
            // Tính tổng số ngày làm việc lý thuyết (số nhân viên × số ngày trong kỳ)
            $totalPossibleDays = $totalEmployees * $totalDaysInPeriod;
            
            // Tính % ngày nghỉ = (Tổng ngày vắng mặt / Tổng ngày lý thuyết) × 100
            $absentPercentage = $totalPossibleDays > 0 
                ? round(($totalAbsent / $totalPossibleDays) * 100, 2) 
                : 0;

            return [
                'department' => $department,
                'department_id' => $deptId,
                'total_employees' => $totalEmployees,
                'total_days' => $totalDays,
                'on_time' => $totalOnTime,
                'late' => $totalLate,
                'early_leave' => $totalEarlyLeave,
                'absent' => $totalAbsent,
                'absent_percentage' => $absentPercentage,
                'total_days_in_period' => $totalDaysInPeriod,
            ];
        })->values();

        return $departmentStats;
    }

    public function export(Request $request)
    {
        $period = $request->get('period', 'month');
        $date = $request->get('date', now()->format('Y-m'));
        
        if ($period === 'day') {
            $startDate = Carbon::parse($date);
            $endDate = $startDate->copy();
        } elseif ($period === 'week') {
            $startDate = Carbon::parse($date)->startOfWeek();
            $endDate = $startDate->copy()->endOfWeek();
        } else {
            $startDate = Carbon::parse($date)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
        }

        $reports = $this->generateReport($startDate, $endDate, $request->get('department_id'));

        $filename = 'bao_cao_cham_cong_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($reports) {
            $file = fopen('php://output', 'w');
            
            // BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['Mã NV', 'Họ tên', 'Phòng ban', 'Tổng ngày', 'Đúng giờ', 'Đi muộn', 'Về sớm', 'Vắng mặt']);
            
            foreach ($reports as $report) {
                fputcsv($file, [
                    $report['user']->code,
                    $report['user']->name,
                    $report['user']->profile->department->name ?? '-',
                    $report['total_days'],
                    $report['on_time'],
                    $report['late'],
                    $report['early_leave'],
                    $report['absent'],
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}


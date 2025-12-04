<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\PayrollRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = now()->format('Y-m-d');
        
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('work_date', $today)
            ->first();

        $monthlyWorkingDays = Attendance::where('user_id', $user->id)
            ->whereMonth('work_date', now()->month)
            ->whereYear('work_date', now()->year)
            ->where('status', '!=', 'absent')
            ->count();

        $pendingLeaves = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $currentMonthSalary = PayrollRecord::where('user_id', $user->id)
            ->whereMonth('period_start', now()->month)
            ->whereYear('period_start', now()->year)
            ->value('net_pay') ?? 0;

        $recentActivities = [];

        return view('dashboard', compact(
            'todayAttendance',
            'monthlyWorkingDays',
            'pendingLeaves',
            'currentMonthSalary',
            'recentActivities'
        ));
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\PayrollRecord;
use App\Models\User;
use App\Services\Payroll\PayrollCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $query = PayrollRecord::with(['user.profile.department', 'user.profile.salaryGrade'])
            ->where('period_start', $startDate->format('Y-m-d'))
            ->where('period_end', $endDate->format('Y-m-d'));

        // Tìm kiếm theo tên, mã nhân viên, email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Lọc theo phòng ban
        if ($request->filled('department_id')) {
            $query->whereHas('user.profile', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $departments = \App\Models\Department::all();

        return view('payroll.index', compact('payrolls', 'month', 'departments'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
        ]);

        $calculator = new PayrollCalculator();
        $records = $calculator->generate(
            Carbon::parse($validated['period_start']),
            Carbon::parse($validated['period_end'])
        );

        return redirect()->route('payroll.index', [
            'month' => Carbon::parse($validated['period_start'])->format('Y-m')
        ])->with('success', "Đã tạo {$records->count()} bảng lương!");
    }

    public function show(PayrollRecord $payroll)
    {
        $payroll->load('user.profile.department', 'user.profile.salaryGrade');
        return view('payroll.show', compact('payroll'));
    }

    public function updateStatus(Request $request, PayrollRecord $payroll)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,confirmed,paid',
        ]);

        $payroll->update(['status' => $validated['status']]);

        return redirect()->route('payroll.index')->with('success', 'Cập nhật trạng thái thành công!');
    }

    public function myPayroll(Request $request)
    {
        $user = auth()->user();
        $month = $request->get('month', now()->format('Y-m'));
        
        $payrolls = PayrollRecord::with(['user.profile.salaryGrade'])
            ->where('user_id', $user->id)
            ->whereYear('period_start', substr($month, 0, 4))
            ->whereMonth('period_start', substr($month, 5, 2))
            ->orderBy('period_start', 'desc')
            ->get();

        return view('payroll.my-payroll', compact('payrolls', 'month'));
    }
}


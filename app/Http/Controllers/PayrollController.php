<?php

namespace App\Http\Controllers;

use App\Models\PayrollRecord;
use App\Services\Payroll\PayrollCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        // when filtering by month we only care about the start date; using an
        // exact end-date match meant that records created for a slightly
        // different range (e.g. end on the 1st of the following month) wouldn't
        // surface. this led to the "created but not shown" symptom described by
        // the user. a between clause on period_start is both simpler and more
        // tolerant.
        $query = PayrollRecord::with(['user.profile.department', 'user.profile.salaryGrade'])
            ->whereBetween('period_start', [$start->format('Y-m-d'), $end->format('Y-m-d')]);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$s}%")
                ->orWhere('code', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%"));
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.profile', fn ($q) => $q->where('department_id', $request->department_id));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $departments = \App\Models\Department::all();

        return view('payroll.index', compact('payrolls', 'month', 'departments'));
    }

    public function create()
    {
        return view('payroll.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date'],
        ], [
            'period_start.required' => 'Vui lòng chọn từ ngày.',
            'period_end.required' => 'Vui lòng chọn đến ngày.',
        ]);

        $start = Carbon::parse($request->period_start)->startOfDay();
        $end = Carbon::parse($request->period_end)->startOfDay();
        if ($end->lt($start)) {
            return back()->withErrors(['period_end' => 'Đến ngày phải sau hoặc trùng với từ ngày.'])->withInput();
        }

        $records = (new PayrollCalculator)->generate($start, $end);

        return redirect()->route('payroll.index', ['month' => $start->format('Y-m')])
            ->with('success', "Đã tạo {$records->count()} bảng lương.");
    }

    public function show(PayrollRecord $payroll)
    {
        $payroll->load('user.profile.department', 'user.profile.salaryGrade');
        return view('payroll.show', compact('payroll'));
    }

    public function updateStatus(Request $request, PayrollRecord $payroll)
    {
        $request->validate(['status' => 'required|in:draft,confirmed,paid']);
        $payroll->update(['status' => $request->status]);
        return redirect()->route('payroll.index')->with('success', 'Cập nhật trạng thái thành công.');
    }

    public function myPayroll(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $payrolls = PayrollRecord::with(['user.profile.salaryGrade'])
            ->where('user_id', auth()->id())
            ->whereYear('period_start', substr($month, 0, 4))
            ->whereMonth('period_start', substr($month, 5, 2))
            ->orderByDesc('period_start')
            ->get();

        return view('payroll.my-payroll', compact('payrolls', 'month'));
    }

    public function export(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $query = PayrollRecord::with(['user.profile.department', 'user.profile.salaryGrade'])
            ->where('period_start', $start->format('Y-m-d'))
            ->where('period_end', $end->format('Y-m-d'));

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"));
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user.profile', fn ($q) => $q->where('department_id', $request->department_id));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->orderByDesc('created_at')->get();
        $filename = 'bang_luong_' . $start->format('Y-m') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($payrolls) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Mã NV', 'Họ tên', 'Email', 'Phòng ban', 'Lương cơ bản', 'Số ngày làm', 'Giờ/ngày', 'OT', 'Phụ cấp', 'Tổng thu', 'BHXH', 'BHYT', 'BHTN', 'Thuế TNCN', 'Khấu trừ', 'Thực lĩnh', 'Trạng thái']);
            foreach ($payrolls as $p) {
                $base = $p->user->profile->base_salary_override ?? $p->user->profile->salaryGrade->base_salary ?? 0;
                $type = $p->user->profile->salaryGrade->salary_type ?? 'monthly';
                $statusText = match ($p->status) { 'draft' => 'Nháp', 'confirmed' => 'Đã xác nhận', 'paid' => 'Đã thanh toán', default => $p->status };
                fputcsv($file, [
                    $p->user->code,
                    $p->user->name,
                    $p->user->email,
                    $p->user->profile->department->name ?? '-',
                    number_format($base, 0, ',', '.') . ($type === 'hourly' ? '/giờ' : ''),
                    number_format($p->working_days, 1),
                    $p->hours_per_day ? number_format($p->hours_per_day, 2) : '-',
                    number_format($p->ot_hours, 2),
                    number_format($p->allowances, 0, ',', '.'),
                    number_format($p->gross_salary, 0, ',', '.'),
                    number_format($p->social_insurance, 0, ',', '.'),
                    number_format($p->health_insurance, 0, ',', '.'),
                    number_format($p->unemployment_insurance, 0, ',', '.'),
                    number_format($p->personal_income_tax, 0, ',', '.'),
                    number_format($p->deductions, 0, ',', '.'),
                    number_format($p->net_pay, 0, ',', '.'),
                    $statusText,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Nhân viên chỉ xem được của mình, quản lý xem được tất cả
        $query = OvertimeRequest::with(['user', 'approver']);
        
        if (!$user->can('manage-employees')) {
            $query->where('user_id', $user->id);
        } else {
            // Quản lý có thể lọc theo nhân viên
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        }
        
        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Lọc theo tháng
        if ($request->filled('month')) {
            $month = Carbon::parse($request->month);
            $query->whereYear('date', $month->year)
                  ->whereMonth('date', $month->month);
        }
        
        $overtimeRequests = $query->orderBy('date', 'desc')
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(20);
        
        return view('overtime-requests.index', compact('overtimeRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('overtime-requests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'nullable|string|max:1000',
        ]);
        
        // Tính số giờ làm thêm
        $start = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $end = Carbon::parse($validated['date'] . ' ' . $validated['end_time']);
        $hours = round($end->diffInMinutes($start) / 60, 2);
        
        // Kiểm tra xem đã có đăng ký cho ngày này chưa
        $existing = OvertimeRequest::where('user_id', Auth::id())
                                   ->where('date', $validated['date'])
                                   ->where('status', '!=', 'rejected')
                                   ->first();
        
        if ($existing) {
            return back()->withErrors(['date' => 'Bạn đã đăng ký làm thêm giờ cho ngày này rồi.'])->withInput();
        }
        
        OvertimeRequest::create([
            'user_id' => Auth::id(),
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'hours' => $hours,
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);
        
        return redirect()->route('overtime-requests.index')->with('success', 'Đăng ký làm thêm giờ thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(OvertimeRequest $overtimeRequest)
    {
        // Kiểm tra quyền xem
        if (!Auth::user()->can('manage-employees') && $overtimeRequest->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('overtime-requests.show', compact('overtimeRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OvertimeRequest $overtimeRequest)
    {
        // Chỉ cho phép sửa nếu là của mình và chưa được duyệt
        if ($overtimeRequest->user_id !== Auth::id() || $overtimeRequest->status !== 'pending') {
            abort(403);
        }
        
        return view('overtime-requests.edit', compact('overtimeRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OvertimeRequest $overtimeRequest)
    {
        // Chỉ cho phép sửa nếu là của mình và chưa được duyệt
        if ($overtimeRequest->user_id !== Auth::id() || $overtimeRequest->status !== 'pending') {
            abort(403);
        }
        
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'nullable|string|max:1000',
        ]);
        
        // Tính số giờ làm thêm
        $start = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $end = Carbon::parse($validated['date'] . ' ' . $validated['end_time']);
        $hours = round($end->diffInMinutes($start) / 60, 2);
        
        // Kiểm tra xem đã có đăng ký khác cho ngày này chưa (trừ chính nó)
        $existing = OvertimeRequest::where('user_id', Auth::id())
                                   ->where('date', $validated['date'])
                                   ->where('id', '!=', $overtimeRequest->id)
                                   ->where('status', '!=', 'rejected')
                                   ->first();
        
        if ($existing) {
            return back()->withErrors(['date' => 'Bạn đã đăng ký làm thêm giờ cho ngày này rồi.'])->withInput();
        }
        
        $overtimeRequest->update([
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'hours' => $hours,
            'reason' => $validated['reason'] ?? null,
        ]);
        
        return redirect()->route('overtime-requests.index')->with('success', 'Cập nhật đăng ký làm thêm giờ thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OvertimeRequest $overtimeRequest)
    {
        // Chỉ cho phép xóa nếu là của mình và chưa được duyệt
        if ($overtimeRequest->user_id !== Auth::id() || $overtimeRequest->status !== 'pending') {
            abort(403);
        }
        
        $overtimeRequest->delete();
        
        return redirect()->route('overtime-requests.index')->with('success', 'Xóa đăng ký làm thêm giờ thành công!');
    }
    
    /**
     * Approve overtime request (chỉ quản lý)
     */
    public function approve(Request $request, OvertimeRequest $overtimeRequest)
    {
        if (!Auth::user()->can('manage-employees')) {
            abort(403);
        }
        
        $overtimeRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        
        return redirect()->route('overtime-requests.index')->with('success', 'Duyệt đăng ký làm thêm giờ thành công!');
    }
    
    /**
     * Reject overtime request (chỉ quản lý)
     */
    public function reject(Request $request, OvertimeRequest $overtimeRequest)
    {
        if (!Auth::user()->can('manage-employees')) {
            abort(403);
        }
        
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);
        
        $overtimeRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ]);
        
        return redirect()->route('overtime-requests.index')->with('success', 'Từ chối đăng ký làm thêm giờ thành công!');
    }
}

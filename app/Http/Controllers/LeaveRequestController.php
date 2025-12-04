<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->canManageEmployees()) {
            $query = LeaveRequest::with(['user', 'approver']);
            
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(15);
        } else {
            $leaveRequests = LeaveRequest::where('user_id', $user->id)
                ->with('approver')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        return view('leave-requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:leave,late,early,remote,other',
            'reason' => 'required|string',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
        ]);

        $startAt = Carbon::parse($validated['start_at']);
        $endAt = Carbon::parse($validated['end_at']);
        $durationHours = $startAt->diffInHours($endAt);

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'reason' => $validated['reason'],
            'start_at' => $startAt,
            'end_at' => $endAt,
            'duration_hours' => $durationHours,
            'status' => 'pending',
        ]);

        return redirect()->route('leave-requests.index')->with('success', 'Gửi đơn xin nghỉ thành công!');
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'review_note' => 'nullable|string',
        ]);

        $leaveRequest->update([
            'status' => 'approved',
            'approver_id' => Auth::id(),
            'review_note' => $request->review_note,
        ]);

        return redirect()->route('leave-requests.index')->with('success', 'Duyệt đơn thành công!');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'approver_id' => Auth::id(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('leave-requests.index')->with('success', 'Từ chối đơn thành công!');
    }
}


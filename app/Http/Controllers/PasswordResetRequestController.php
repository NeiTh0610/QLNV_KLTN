<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\Request;

class PasswordResetRequestController extends Controller
{
    // Guest: show form to request password reset
    public function create()
    {
        return view('auth.forgot-password');
    }

    // Guest: store request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $validated['email'])->first();

        PasswordResetRequest::create([
            'user_id' => $user?->id,
            'email' => $validated['email'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Yêu cầu quên mật khẩu đã được gửi. Quản trị viên sẽ liên hệ và đặt lại mật khẩu cho bạn.');
    }

    // Admin: list all requests
    public function index()
    {
        $requests = PasswordResetRequest::with('user')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('password-requests.index', compact('requests'));
    }

    // Admin: update status/note
    public function update(Request $request, PasswordResetRequest $passwordRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processed,rejected',
            'note' => 'nullable|string|max:1000',
        ]);

        $passwordRequest->update($validated);

        return back()->with('success', 'Cập nhật trạng thái yêu cầu thành công.');
    }
}


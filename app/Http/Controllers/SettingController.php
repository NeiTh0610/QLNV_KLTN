<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Holiday;
use App\Models\WorkShift;
use App\Support\Settings;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function index()
    {
        // Kiểm tra quyền admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Chỉ quản trị viên mới có quyền truy cập.');
        }
        
        $workShifts = WorkShift::all();
        $holidays = Holiday::orderBy('date', 'asc')->get();
        $departments = Department::all();
        
        $settings = [
            'working_hours_start' => Settings::get('attendance.working_hours.start', '08:00'),
            'working_hours_end' => Settings::get('attendance.working_hours.end', '17:00'),
            'late_threshold' => Settings::get('attendance.late.threshold_minutes', 15),
            'early_leave_threshold' => Settings::get('attendance.early_leave.threshold_minutes', 15),
        ];

        return view('settings.index', compact('workShifts', 'holidays', 'departments', 'settings'));
    }

    public function updateSettings(Request $request)
    {
        // Kiểm tra quyền admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Chỉ quản trị viên mới có quyền truy cập.');
        }
        
        $validated = $request->validate([
            'working_hours_start' => 'required|date_format:H:i',
            'working_hours_end' => 'required|date_format:H:i',
            'late_threshold' => 'required|integer|min:0',
            'early_leave_threshold' => 'required|integer|min:0',
        ]);

        Settings::set('attendance.working_hours.start', $validated['working_hours_start']);
        Settings::set('attendance.working_hours.end', $validated['working_hours_end']);
        Settings::set('attendance.late.threshold_minutes', $validated['late_threshold']);
        Settings::set('attendance.early_leave.threshold_minutes', $validated['early_leave_threshold']);

        return redirect()->route('settings.index')->with('success', 'Cập nhật cài đặt thành công!');
    }

    public function storeHoliday(Request $request)
    {
        // Kiểm tra quyền admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Chỉ quản trị viên mới có quyền truy cập.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:date',
            'is_recurring' => 'nullable|boolean',
            'compensate_to' => 'nullable|date',
        ]);

        Holiday::create([
            'name' => $validated['name'],
            'date' => $validated['date'],
            'end_date' => $validated['end_date'] ?? null,
            'is_recurring' => $request->has('is_recurring') ? true : false,
            'compensate_to' => $validated['compensate_to'] ?? null,
        ]);

        return redirect()->route('settings.index')->with('success', 'Thêm ngày lễ thành công!');
    }

    public function updateHoliday(Request $request, Holiday $holiday)
    {
        // Kiểm tra quyền admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Chỉ quản trị viên mới có quyền truy cập.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:date',
            'is_recurring' => 'nullable|boolean',
            'compensate_to' => 'nullable|date',
        ]);

        $holiday->update([
            'name' => $validated['name'],
            'date' => $validated['date'],
            'end_date' => $validated['end_date'] ?? null,
            'is_recurring' => $request->has('is_recurring') ? true : false,
            'compensate_to' => $validated['compensate_to'] ?? null,
        ]);

        return redirect()->route('settings.index')->with('success', 'Cập nhật ngày lễ thành công!');
    }

    public function destroyHoliday(Holiday $holiday)
    {
        // Kiểm tra quyền admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Chỉ quản trị viên mới có quyền truy cập.');
        }
        
        $holiday->delete();

        return redirect()->route('settings.index')->with('success', 'Xóa ngày lễ thành công!');
    }
}


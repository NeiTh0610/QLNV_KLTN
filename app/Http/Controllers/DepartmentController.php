<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::with(['manager', 'profiles']);

        // Tìm kiếm theo tên, mô tả
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $departments = $query->orderBy('name')->paginate(15)->withQueryString();

        // Thêm số lượng nhân viên cho mỗi phòng ban
        foreach ($departments as $department) {
            $department->employee_count = $department->profiles()->count();
        }

        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        $managers = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'manager']);
        })->get();

        return view('departments.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Thêm phòng ban thành công!');
    }

    public function show(Department $department)
    {
        $department->load(['manager', 'profiles.user']);
        $employeeCount = $department->profiles()->count();

        return view('departments.show', compact('department', 'employeeCount'));
    }

    public function edit(Department $department)
    {
        $managers = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'manager']);
        })->get();

        return view('departments.edit', compact('department', 'managers'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Cập nhật phòng ban thành công!');
    }

    public function destroy(Department $department)
    {
        // Kiểm tra xem phòng ban có nhân viên không
        $employeeCount = $department->profiles()->count();
        
        if ($employeeCount > 0) {
            return redirect()->route('departments.index')
                ->with('error', 'Không thể xóa phòng ban này vì còn ' . $employeeCount . ' nhân viên đang làm việc!');
        }

        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Xóa phòng ban thành công!');
    }
}


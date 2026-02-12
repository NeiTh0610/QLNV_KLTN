<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Role;
use App\Models\SalaryGrade;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['profile.department', 'profile.salaryGrade', 'roles'])
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['employee', 'part_time', 'department_manager']);
            });

        // Tìm kiếm theo tên, email, mã nhân viên, số điện thoại
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Lọc theo phòng ban
        if ($request->filled('department_id')) {
            $query->whereHas('profile', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $departments = Department::all();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function show(User $employee)
    {
        $employee->load('profile.department', 'profile.salaryGrade', 'roles');
        return view('employees.show', compact('employee'));
    }

    public function create()
    {
        $departments = Department::all();
        $roles = Role::whereIn('name', ['employee', 'part_time', 'department_manager'])->get();
        $salaryGrades = SalaryGrade::all();

        return view('employees.create', compact('departments', 'roles', 'salaryGrades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:users,code',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|unique:users,phone',
            'password' => 'required|string|min:8',
            'status' => 'required|in:active,inactive',
            'hired_at' => 'nullable|date',
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'nullable|string',
            'role_id' => 'required|exists:roles,id',
            'salary_grade_id' => 'nullable|exists:salary_grades,id',
            'base_salary_override' => 'nullable|numeric|min:0',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        DB::transaction(function() use ($validated) {
            $user = User::create([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'status' => $validated['status'],
                'hired_at' => $validated['hired_at'] ?? now(),
                'email_verified_at' => now(),
            ]);

            // avatar upload
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('users', 'public');
                $user->avatar_path = $path;
                $user->save();
            }

            $user->roles()->attach($validated['role_id']);

            $profile = UserProfile::create([
                'user_id' => $user->id,
                'department_id' => $validated['department_id'] ?? null,
                'position' => $validated['position'] ?? null,
                'salary_grade_id' => $validated['salary_grade_id'] ?? null,
                'base_salary_override' => $validated['base_salary_override'] ?? null,
            ]);

            // Tự động cập nhật trưởng phòng nếu role là department_manager và có phòng ban
            $role = Role::find($validated['role_id']);
            if ($role && $role->name === 'department_manager' && $profile->department_id) {
                Department::where('id', $profile->department_id)
                    ->update(['manager_id' => $user->id]);
            }
        });

        return redirect()->route('employees.index')->with('success', 'Thêm nhân viên thành công!');
    }

    public function edit(User $employee)
    {
        $departments = Department::all();
        $roles = Role::whereIn('name', ['employee', 'part_time', 'department_manager'])->get();
        $salaryGrades = SalaryGrade::all();
        $employee->load('profile', 'roles');

        return view('employees.edit', compact('employee', 'departments', 'roles', 'salaryGrades'));
    }

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:users,code,' . $employee->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string|unique:users,phone,' . $employee->id,
            'password' => 'nullable|string|min:8',
            'status' => 'required|in:active,inactive',
            'hired_at' => 'nullable|date',
            'left_at' => 'nullable|date',
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'nullable|string',
            'role_id' => 'required|exists:roles,id',
            'salary_grade_id' => 'nullable|exists:salary_grades,id',
            'base_salary_override' => 'nullable|numeric|min:0',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        DB::transaction(function() use ($validated, $employee) {
            $updateData = [
                'code' => $validated['code'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'status' => $validated['status'],
                'hired_at' => $validated['hired_at'] ?? $employee->hired_at,
                'left_at' => $validated['left_at'] ?? null,
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            // handle avatar upload
            if ($request->hasFile('avatar')) {
                if ($employee->avatar_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($employee->avatar_path);
                }
                $path = $request->file('avatar')->store('users', 'public');
                $updateData['avatar_path'] = $path;
            }
            $employee->update($updateData);

            // Lấy role cũ để kiểm tra
            $oldRole = $employee->roles()->first();
            $oldDepartmentId = $employee->profile->department_id ?? null;

            $employee->roles()->sync([$validated['role_id']]);

            // Lấy role mới
            $newRole = Role::find($validated['role_id']);
            $newDepartmentId = $validated['department_id'] ?? null;

            $employee->profile()->updateOrCreate(
                ['user_id' => $employee->id],
                [
                    'department_id' => $newDepartmentId,
                    'position' => $validated['position'] ?? null,
                    'salary_grade_id' => $validated['salary_grade_id'] ?? null,
                    'base_salary_override' => $validated['base_salary_override'] ?? null,
                ]
            );

            // Xử lý tự động cập nhật trưởng phòng
            // Nếu đổi role từ department_manager sang role khác, xóa manager_id của phòng ban cũ
            if ($oldRole && $oldRole->name === 'department_manager' && $oldDepartmentId) {
                // Chỉ xóa nếu manager hiện tại là nhân viên này
                Department::where('id', $oldDepartmentId)
                    ->where('manager_id', $employee->id)
                    ->update(['manager_id' => null]);
            }

            // Nếu đổi phòng ban và vẫn là trưởng phòng, cần xóa manager_id của phòng ban cũ
            if ($oldDepartmentId && $oldDepartmentId != $newDepartmentId && 
                $oldRole && $oldRole->name === 'department_manager') {
                Department::where('id', $oldDepartmentId)
                    ->where('manager_id', $employee->id)
                    ->update(['manager_id' => null]);
            }

            // Nếu role mới là department_manager và có phòng ban, tự động set làm trưởng phòng
            if ($newRole && $newRole->name === 'department_manager' && $newDepartmentId) {
                Department::where('id', $newDepartmentId)
                    ->update(['manager_id' => $employee->id]);
            }
        });

        return redirect()->route('employees.index')->with('success', 'Cập nhật nhân viên thành công!');
    }

    public function destroy(User $employee)
    {
        DB::transaction(function() use ($employee) {
            // Nếu nhân viên là trưởng phòng, xóa manager_id của phòng ban
            if ($employee->hasRole('department_manager') && $employee->profile && $employee->profile->department_id) {
                Department::where('id', $employee->profile->department_id)
                    ->where('manager_id', $employee->id)
                    ->update(['manager_id' => null]);
            }

            $employee->delete();
        });

        return redirect()->route('employees.index')->with('success', 'Xóa nhân viên thành công!');
    }
}


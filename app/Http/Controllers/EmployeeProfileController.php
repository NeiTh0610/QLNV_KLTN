<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeProfileController extends Controller
{
    /**
     * Kiểm tra quyền admin
     */
    private function checkAdmin()
    {
        if (!auth()->check() || !auth()->user()->hasRole('admin')) {
            abort(403, 'Chỉ quản trị viên mới có quyền truy cập.');
        }
    }

    /**
     * Hiển thị danh sách hồ sơ nhân viên
     */
    public function index(Request $request)
    {
        $this->checkAdmin();
        
        $query = User::with(['profile.department', 'profile.salaryGrade', 'roles'])
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['employee', 'part_time']);
            })
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('profile', function($q) use ($search) {
                      $q->where('id_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('department_id')) {
            $query->whereHas('profile', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        $employees = $query->paginate(15)->withQueryString();
        $departments = \App\Models\Department::all();

        return view('employee-profiles.index', compact('employees', 'departments'));
    }

    /**
     * Hiển thị form chỉnh sửa hồ sơ nhân viên
     */
    public function edit(User $employee)
    {
        $this->checkAdmin();
        $employee->load(['profile.department', 'profile.salaryGrade']);
        $departments = \App\Models\Department::all();
        $salaryGrades = \App\Models\SalaryGrade::where('is_active', true)->get();

        return view('employee-profiles.edit', compact('employee', 'departments', 'salaryGrades'));
    }

    /**
     * Cập nhật hồ sơ nhân viên
     */
    public function update(Request $request, User $employee)
    {
        $this->checkAdmin();
        $validated = $request->validate([
            // Thông tin cơ bản
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'code' => 'required|string|max:50|unique:users,code,' . $employee->id,
            
            // Thông tin hồ sơ
            'id_number' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'personal_email' => 'nullable|email|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            
            // Học vấn
            'education_level' => 'nullable|string|max:100',
            'major' => 'nullable|string|max:255',
            'university' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer|min:0',
            'skills' => 'nullable|string',
            'certifications' => 'nullable|string',
            'previous_work' => 'nullable|string',
            
            // Thông tin công việc
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'nullable|string|max:255',
            'salary_grade_id' => 'nullable|exists:salary_grades,id',
            'base_salary_override' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function() use ($validated, $employee) {
            // Cập nhật thông tin user
            $employee->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'code' => $validated['code'],
            ]);

            // Cập nhật hoặc tạo profile
            $profileData = [
                'id_number' => $validated['id_number'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'address' => $validated['address'] ?? null,
                'personal_email' => $validated['personal_email'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
                'education_level' => $validated['education_level'] ?? null,
                'major' => $validated['major'] ?? null,
                'university' => $validated['university'] ?? null,
                'years_of_experience' => $validated['years_of_experience'] ?? 0,
                'skills' => $validated['skills'] ?? null,
                'certifications' => $validated['certifications'] ?? null,
                'previous_work' => $validated['previous_work'] ?? null,
                'department_id' => $validated['department_id'] ?? null,
                'position' => $validated['position'] ?? null,
                'salary_grade_id' => $validated['salary_grade_id'] ?? null,
                'base_salary_override' => $validated['base_salary_override'] ?? null,
            ];

            $employee->profile()->updateOrCreate(
                ['user_id' => $employee->id],
                $profileData
            );
        });

        return redirect()->route('employee-profiles.index')
            ->with('success', 'Cập nhật hồ sơ nhân viên thành công!');
    }

    /**
     * Hiển thị chi tiết hồ sơ nhân viên
     */
    public function show(User $employee)
    {
        $this->checkAdmin();
        $employee->load(['profile.department', 'profile.salaryGrade', 'contracts' => function($q) {
            $q->orderBy('start_date', 'desc');
        }]);

        return view('employee-profiles.show', compact('employee'));
    }
}

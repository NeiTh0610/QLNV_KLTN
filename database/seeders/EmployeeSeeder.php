<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $roles = DB::table('roles')->pluck('id', 'name');

        $departments = DB::table('departments')
            ->pluck('id', 'name');

        $salaryGrades = DB::table('salary_grades')
            ->pluck('id', 'code');

        $employees = [
            [
                'code' => 'EMP-1001',
                'name' => 'Nguyễn Văn A',
                'email' => 'nguyenvana@example.com',
                'phone' => '0911000001',
                'department' => 'Phòng Nhân sự',
                'position' => 'Chuyên viên nhân sự',
                'role' => 'employee',
                'salary_grade' => 'FULL-JR', // Tất cả nhân viên dùng cùng thang lương
            ],
            [
                'code' => 'EMP-1002',
                'name' => 'Trần Thị B',
                'email' => 'tranthib@example.com',
                'phone' => '0911000002',
                'department' => 'Phòng Kỹ thuật',
                'position' => 'Kỹ sư phần mềm',
                'role' => 'employee',
                'salary_grade' => 'FULL-JR', // Tất cả nhân viên dùng cùng thang lương
            ],
            [
                'code' => 'EMP-1003',
                'name' => 'Lê Văn C',
                'email' => 'levanc@example.com',
                'phone' => '0911000003',
                'department' => 'Phòng Kế toán',
                'position' => 'Kế toán viên',
                'role' => 'employee',
                'salary_grade' => 'FULL-JR', // Tất cả nhân viên dùng cùng thang lương
            ],
            [
                'code' => 'EMP-1004',
                'name' => 'Phạm Thị D',
                'email' => 'phamthid@example.com',
                'phone' => '0911000004',
                'department' => 'Phòng Hành chính',
                'position' => 'Nhân viên hành chính',
                'role' => 'employee',
                'salary_grade' => 'FULL-JR', // Tất cả nhân viên dùng cùng thang lương
            ],
            [
                'code' => 'EMP-1005',
                'name' => 'Đỗ Văn E',
                'email' => 'dovane@example.com',
                'phone' => '0911000005',
                'department' => 'Phòng Kinh doanh',
                'position' => 'Chuyên viên kinh doanh',
                'role' => 'employee',
                'salary_grade' => 'FULL-JR', // Tất cả nhân viên dùng cùng thang lương
            ],
            [
                'code' => 'EMP-PT01',
                'name' => 'Vũ Minh F',
                'email' => 'vuminhf@example.com',
                'phone' => '0911000006',
                'department' => 'Phòng Hành chính',
                'position' => 'Nhân viên hỗ trợ part-time',
                'role' => 'part_time',
                'salary_grade' => 'PT-L1',
                'base_salary_override' => null,
            ],
        ];

        foreach ($employees as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'code' => $data['code'],
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'password' => Hash::make('123456'),
                    'status' => 'active',
                    'avatar_path' => null,
                    'hired_at' => $now->copy()->subMonths(rand(1, 24))->toDateString(),
                    'left_at' => null,
                    'remember_token' => Str::random(10),
                    'email_verified_at' => $now,
                ]
            );

            $roleId = $roles->get($data['role'] ?? 'employee');

            if ($roleId) {
                DB::table('role_user')->upsert(
                    [
                        [
                            'role_id' => $roleId,
                            'user_id' => $user->id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ],
                    ],
                    ['role_id', 'user_id'],
                    ['updated_at']
                );
            }

            $departmentId = $departments->get($data['department']);
            $salaryGradeId = isset($data['salary_grade'])
                ? $salaryGrades->get($data['salary_grade'])
                : null;

            DB::table('user_profiles')->upsert(
                [
                    [
                        'user_id' => $user->id,
                        'department_id' => $departmentId,
                        'position' => $data['position'],
                        'salary_grade_id' => $salaryGradeId,
                        'base_salary_override' => $data['base_salary_override'] ?? null,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ],
                ],
                ['user_id'],
                ['department_id', 'position', 'salary_grade_id', 'base_salary_override', 'updated_at']
            );
        }
    }
}


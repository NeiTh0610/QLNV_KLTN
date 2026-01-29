<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdditionalEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $roles = DB::table('roles')->pluck('id', 'name');
        $departments = DB::table('departments')->pluck('id', 'name');
        $salaryGrades = DB::table('salary_grades')->pluck('id', 'code');

        // 10 nhân viên mới chia đều vào các phòng ban
        $employees = [
            // Phòng Nhân sự (2 nhân viên)
            [
                'code' => 'EMP-1007',
                'name' => 'Hoàng Thị G',
                'email' => 'hoangthig@example.com',
                'phone' => '0911000007',
                'department' => 'Phòng Nhân sự',
                'position' => 'Chuyên viên tuyển dụng',
                'role' => 'employee',
                'salary_grade' => 'FULL-JR',
            ],
            [
                'code' => 'EMP-1008',
                'name' => 'Bùi Văn H',
                'email' => 'buivanh@example.com',
                'phone' => '0911000008',
                'department' => 'Phòng Nhân sự',
                'position' => 'Chuyên viên đào tạo',
                'role' => 'employee',
                'salary_grade' => 'FULL-JR',
            ],
            // Phòng Kỹ thuật (2 nhân viên)
            [
                'code' => 'EMP-1009',
                'name' => 'Đinh Thị I',
                'email' => 'dinhthi@example.com',
                'phone' => '0911000009',
                'department' => 'Phòng Kỹ thuật',
                'position' => 'Kỹ sư phát triển',
                'role' => 'employee',
                'salary_grade' => 'FULL-JR',
            ],
            [
                'code' => 'EMP-1010',
                'name' => 'Ngô Văn K',
                'email' => 'ngovank@example.com',
                'phone' => '0911000010',
                'department' => 'Phòng Kỹ thuật',
                'position' => 'Kỹ sư kiểm thử',
                'role' => 'employee',
                'salary_grade' => 'FULL-JR',
            ],
            // Phòng Kế toán (2 nhân viên)
            [
                'code' => 'EMP-1011',
                'name' => 'Lý Thị L',
                'email' => 'lythil@example.com',
                'phone' => '0911000011',
                'department' => 'Phòng Kế toán',
                'position' => 'Kế toán viên',
                'role' => 'employee',
                'salary_grade' => 'FULL-JR',
            ],
            [
                'code' => 'EMP-1012',
                'name' => 'Trịnh Văn M',
                'email' => 'trinhvanm@example.com',
                'phone' => '0911000012',
                'department' => 'Phòng Kế toán',
                'position' => 'Kế toán trưởng',
                'role' => 'employee',
                'salary_grade' => 'FULL-SR',
            ],
            // Phòng Hành chính (2 nhân viên)
            [
                'code' => 'EMP-1013',
                'name' => 'Võ Thị N',
                'email' => 'vothin@example.com',
                'phone' => '0911000013',
                'department' => 'Phòng Hành chính',
                'position' => 'Nhân viên văn thư',
                'role' => 'employee',
                'salary_grade' => 'FULL-JR',
            ],
            [
                'code' => 'EMP-1014',
                'name' => 'Phan Văn O',
                'email' => 'phanvano@example.com',
                'phone' => '0911000014',
                'department' => 'Phòng Hành chính',
                'position' => 'Nhân viên tổ chức sự kiện',
                'role' => 'employee',
                'salary_grade' => 'FULL-JR',
            ],
            // Phòng Kinh doanh (2 nhân viên)
            [
                'code' => 'EMP-1015',
                'name' => 'Dương Thị P',
                'email' => 'duongthip@example.com',
                'phone' => '0911000015',
                'department' => 'Phòng Kinh doanh',
                'position' => 'Chuyên viên marketing',
                'role' => 'employee',
                'salary_grade' => 'FULL-JR',
            ],
            [
                'code' => 'EMP-1016',
                'name' => 'Lương Văn Q',
                'email' => 'luongvanq@example.com',
                'phone' => '0911000016',
                'department' => 'Phòng Kinh doanh',
                'position' => 'Nhân viên chăm sóc khách hàng',
                'role' => 'employee',
                'salary_grade' => 'FULL-JR',
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

        $this->command->info('Đã thêm ' . count($employees) . ' nhân viên mới thành công!');
    }
}


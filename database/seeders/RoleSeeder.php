<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Quản trị hệ thống, toàn quyền quản lý.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Quản lý trực tiếp, duyệt đơn và xem báo cáo.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'employee',
                'display_name' => 'Employee',
                'description' => 'Nhân viên, sử dụng chấm công và đăng ký nghỉ.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'part_time',
                'display_name' => 'Part-time Employee',
                'description' => 'Nhân viên làm việc bán thời gian, tính lương theo giờ.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'department_manager',
                'display_name' => 'Trưởng phòng',
                'description' => 'Trưởng phòng, quản lý phòng ban và nhân viên trong phòng.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('roles')->upsert(
            $roles,
            ['name'],
            ['display_name', 'description', 'updated_at']
        );
    }
}


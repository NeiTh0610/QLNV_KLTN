<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalaryGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $grades = [
            [
                'code' => 'FULL-JR',
                'name' => 'Nhân viên chính thức - Junior',
                'salary_type' => 'monthly',
                'base_salary' => 10000000, // Tất cả nhân viên: 10 triệu
                'allowance_percent' => 5,
                'description' => 'Nhân viên chính thức mới vào hoặc kinh nghiệm < 2 năm.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'FULL-MID',
                'name' => 'Nhân viên chính thức - Middle',
                'salary_type' => 'monthly',
                'base_salary' => 10000000, // Tất cả nhân viên: 10 triệu
                'allowance_percent' => 10,
                'description' => 'Nhân viên chính thức kinh nghiệm 2-4 năm.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'FULL-SR',
                'name' => 'Nhân viên chính thức - Senior',
                'salary_type' => 'monthly',
                'base_salary' => 10000000, // Tất cả nhân viên: 10 triệu
                'allowance_percent' => 15,
                'description' => 'Nhân viên chính thức kinh nghiệm cao, chuyên môn sâu.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'MGR',
                'name' => 'Quản lý',
                'salary_type' => 'monthly',
                'base_salary' => 30000000, // Admin: 30 triệu (cao nhất)
                'allowance_percent' => 20,
                'description' => 'Cấp quản lý/phòng ban.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'PT-L1',
                'name' => 'Part-time Level 1',
                'salary_type' => 'hourly',
                'base_salary' => 45000,
                'allowance_percent' => 0,
                'description' => 'Nhân viên part-time mới, lương theo giờ.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'PT-L2',
                'name' => 'Part-time Level 2',
                'salary_type' => 'hourly',
                'base_salary' => 60000,
                'allowance_percent' => 0,
                'description' => 'Nhân viên part-time giàu kinh nghiệm.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('salary_grades')->upsert(
            $grades,
            ['code'],
            ['name', 'salary_type', 'base_salary', 'allowance_percent', 'description', 'is_active', 'updated_at']
        );
    }
}


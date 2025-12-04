<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $departments = [
            [
                'name' => 'Phòng Nhân sự',
                'description' => 'Quản lý nhân sự và chấm công.',
                'manager_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Phòng Kỹ thuật',
                'description' => 'Phát triển và vận hành hệ thống.',
                'manager_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Phòng Kế toán',
                'description' => 'Quản lý tài chính, kế toán, báo cáo chi phí.',
                'manager_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Phòng Hành chính',
                'description' => 'Điều phối hành chính, văn thư, cơ sở vật chất.',
                'manager_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Phòng Kinh doanh',
                'description' => 'Phát triển kinh doanh và chăm sóc khách hàng.',
                'manager_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('departments')->upsert(
            $departments,
            ['name'],
            ['description', 'manager_id', 'updated_at']
        );
    }
}


<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');

        if (! $adminRoleId) {
            return;
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@goldenbown.com'],
            [
                'code' => 'EMP-0001',
                'name' => 'System Administrator',
                'phone' => '0983383135',
                'password' => Hash::make('Admin@123'),
                'avatar_path' => null,
                'status' => 'active',
                'hired_at' => $now->toDateString(),
                'left_at' => null,
                'remember_token' => Str::random(10),
                'email_verified_at' => $now,
            ],
        );

        DB::table('role_user')->upsert(
            [
                [
                    'role_id' => $adminRoleId,
                    'user_id' => $admin->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ],
            ['role_id', 'user_id'],
            ['updated_at']
        );

        DB::table('user_profiles')->upsert(
            [
                [
                    'user_id' => $admin->id,
                    'position' => 'Administrator',
                    'department_id' => DB::table('departments')->where('name', 'Phòng Nhân sự')->value('id'),
                    'salary_grade_id' => DB::table('salary_grades')->where('code', 'MGR')->value('id'),
                    'base_salary_override' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ],
            ['user_id'],
            ['position', 'department_id', 'salary_grade_id', 'base_salary_override', 'updated_at']
        );
    }
}


<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,
            SalaryGradeSeeder::class,
            WorkShiftSeeder::class,
            SettingSeeder::class,
            HolidaySeeder::class,
            EmployeeSeeder::class,
            AdminUserSeeder::class,
            EmployeeProfileAndContractSeeder::class,
            AttendanceSampleSeeder::class,
            PayrollSampleSeeder::class,
        ]);
    }
}

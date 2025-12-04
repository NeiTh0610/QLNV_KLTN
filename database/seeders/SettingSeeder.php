<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $settings = [
            [
                'group' => 'attendance',
                'key' => 'working_hours.start',
                'value' => '08:00',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'group' => 'attendance',
                'key' => 'working_hours.end',
                'value' => '17:00',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'group' => 'attendance',
                'key' => 'late.threshold_minutes',
                'value' => '15',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'group' => 'attendance',
                'key' => 'early_leave.threshold_minutes',
                'value' => '15',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'group' => 'attendance',
                'key' => 'company.allowed_wifi_ids',
                'value' => json_encode([]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'group' => 'payroll',
                'key' => 'payroll.personal_deduction',
                'value' => '11000000',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'group' => 'payroll',
                'key' => 'payroll.dependent_deduction',
                'value' => '4400000',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'group' => 'payroll',
                'key' => 'payroll.bhxh_rate_employee',
                'value' => '0.08',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'group' => 'payroll',
                'key' => 'payroll.bhyt_rate_employee',
                'value' => '0.015',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'group' => 'payroll',
                'key' => 'payroll.bhtn_rate_employee',
                'value' => '0.01',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'group' => 'payroll',
                'key' => 'payroll.insurance_base_cap',
                'value' => '29800000',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('settings')->upsert(
            $settings,
            ['key'],
            ['value', 'group', 'updated_at']
        );
    }
}


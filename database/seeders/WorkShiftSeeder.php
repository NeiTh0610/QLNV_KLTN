<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $shift = [
            'name' => 'Ca hành chính',
            'start_time' => '08:00:00',
            'end_time' => '17:00:00',
            'break_minutes' => 60,
            'is_default' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $existing = DB::table('work_shifts')->where('name', $shift['name'])->first();
        
        if ($existing) {
            DB::table('work_shifts')->where('id', $existing->id)->update([
                'start_time' => $shift['start_time'],
                'end_time' => $shift['end_time'],
                'break_minutes' => $shift['break_minutes'],
                'is_default' => $shift['is_default'],
                'updated_at' => $now,
            ]);
        } else {
            DB::table('work_shifts')->insert($shift);
        }
    }
}


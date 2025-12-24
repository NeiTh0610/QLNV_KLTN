<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $year = now()->format('Y');

        $holidays = [
            [
                'name' => 'Tết Dương lịch',
                'date' => "{$year}-01-01",
                'is_recurring' => true,
                'compensate_to' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Ngày Quốc khánh',
                'date' => "{$year}-09-02",
                'is_recurring' => true,
                'compensate_to' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($holidays as $holiday) {
            $existing = DB::table('holidays')->where('name', $holiday['name'])->first();
            
            if ($existing) {
                DB::table('holidays')->where('id', $existing->id)->update([
                    'date' => $holiday['date'],
                    'is_recurring' => $holiday['is_recurring'],
                    'compensate_to' => $holiday['compensate_to'],
                    'updated_at' => $now,
                ]);
            } else {
                DB::table('holidays')->insert($holiday);
            }
        }
    }
}


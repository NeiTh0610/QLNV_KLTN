<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSampleSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['employee', 'part_time']);
        })->get();

        foreach ($users as $user) {
            // Tạo attendance cho tháng 11/2025
            $startDate = Carbon::parse('2025-11-01');
            $endDate = Carbon::parse('2025-11-17'); // Đến hôm nay
            
            $current = $startDate->copy();
            while ($current->lte($endDate)) {
                // Chỉ tạo cho ngày làm việc (T2-T6)
                if ($current->isWeekday()) {
                    $workDate = $current->format('Y-m-d');
                    
                    // Random: 80% đi làm, 20% vắng
                    if (rand(1, 100) <= 80) {
                        // Random giờ check-in (7:30 - 9:00)
                        $checkInHour = rand(7, 8);
                        $checkInMinute = rand(0, 59);
                        $checkIn = Carbon::parse("$workDate $checkInHour:$checkInMinute:00");
                        
                        // Random giờ check-out (16:30 - 18:30)
                        $checkOutHour = rand(16, 18);
                        $checkOutMinute = rand(0, 59);
                        $checkOut = Carbon::parse("$workDate $checkOutHour:$checkOutMinute:00");
                        
                        // Xác định trạng thái
                        $status = 'on_time';
                        $standardStart = Carbon::parse("$workDate 08:00:00");
                        $standardEnd = Carbon::parse("$workDate 17:00:00");
                        
                        if ($checkIn->gt($standardStart->copy()->addMinutes(15))) {
                            $status = 'late';
                        } elseif ($checkOut->lt($standardEnd->copy()->subMinutes(15))) {
                            $status = 'early_leave';
                        }
                        
                        Attendance::create([
                            'user_id' => $user->id,
                            'work_date' => $workDate,
                            'check_in_at' => $checkIn,
                            'check_in_method' => 'manual',
                            'check_in_ip' => '127.0.0.1',
                            'check_out_at' => $checkOut,
                            'check_out_method' => 'manual',
                            'check_out_ip' => '127.0.0.1',
                            'status' => $status,
                        ]);
                    }
                }
                $current->addDay();
            }
        }
    }
}


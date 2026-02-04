<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\OvertimeRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSampleSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy tất cả nhân viên (bao gồm cả trưởng phòng)
        $users = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['employee', 'part_time', 'department_manager']);
        })->where('status', 'active')->get();

        if ($users->isEmpty()) {
            $this->command->warn('Không tìm thấy nhân viên nào để tạo dữ liệu chấm công!');
            return;
        }

        $this->command->info("Đang tạo dữ liệu chấm công cho {$users->count()} nhân viên...");

        // Tạo dữ liệu cho các tháng: 11/2025, 12/2025, 1/2026, 2/2026
        $currentMonth = now();
        $months = [
            ['start' => '2025-11-01', 'end' => '2025-11-30'],
            ['start' => '2025-12-01', 'end' => '2025-12-31'],
            ['start' => '2026-01-01', 'end' => '2026-01-31'],
            ['start' => '2026-02-01', 'end' => $currentMonth->format('Y-m-d')], // Đến ngày hiện tại
        ];

        $totalRecords = 0;
        $overtimeRequests = [];

        foreach ($users as $user) {
            foreach ($months as $month) {
                $startDate = Carbon::parse($month['start']);
                $endDate = Carbon::parse($month['end']);
                
                $current = $startDate->copy();
                while ($current->lte($endDate)) {
                    // Chỉ tạo cho ngày làm việc (T2-T6)
                    if ($current->isWeekday()) {
                        $workDate = $current->format('Y-m-d');
                        
                        // Kiểm tra xem đã có dữ liệu chưa
                        $existing = Attendance::where('user_id', $user->id)
                            ->where('work_date', $workDate)
                            ->first();
                        
                        if ($existing) {
                            $current->addDay();
                            continue;
                        }
                        
                        // Random: 85% đi làm, 15% vắng
                        if (rand(1, 100) <= 85) {
                            $scenario = rand(1, 100);
                            
                            // 50% đi đúng giờ
                            if ($scenario <= 50) {
                                $checkIn = Carbon::parse("$workDate 08:00:00")->addMinutes(rand(-10, 10));
                                $checkOut = Carbon::parse("$workDate 17:00:00")->addMinutes(rand(-10, 10));
                                $status = 'on_time';
                            }
                            // 15% đi muộn < 30 phút
                            elseif ($scenario <= 65) {
                                $checkIn = Carbon::parse("$workDate 08:00:00")->addMinutes(rand(1, 29));
                                $checkOut = Carbon::parse("$workDate 17:00:00")->addMinutes(rand(-10, 10));
                                $status = 'late';
                            }
                            // 10% đi muộn >= 30 phút (nửa ngày)
                            elseif ($scenario <= 75) {
                                $checkIn = Carbon::parse("$workDate 08:00:00")->addMinutes(rand(30, 60));
                                $checkOut = Carbon::parse("$workDate 17:00:00")->addMinutes(rand(-10, 10));
                                $status = 'late';
                            }
                            // 10% về sớm < 30 phút
                            elseif ($scenario <= 85) {
                                $checkIn = Carbon::parse("$workDate 08:00:00")->addMinutes(rand(-10, 10));
                                $checkOut = Carbon::parse("$workDate 17:00:00")->subMinutes(rand(1, 29));
                                $status = 'early_leave';
                            }
                            // 5% về sớm >= 30 phút (nửa ngày)
                            elseif ($scenario <= 90) {
                                $checkIn = Carbon::parse("$workDate 08:00:00")->addMinutes(rand(-10, 10));
                                $checkOut = Carbon::parse("$workDate 17:00:00")->subMinutes(rand(30, 60));
                                $status = 'early_leave';
                            }
                            // 10% làm thêm giờ
                            else {
                                $checkIn = Carbon::parse("$workDate 08:00:00")->addMinutes(rand(-10, 10));
                                // Làm thêm 1-3 giờ
                                $overtimeHours = rand(1, 3);
                                $checkOut = Carbon::parse("$workDate 17:00:00")->addHours($overtimeHours)->addMinutes(rand(-10, 10));
                                $status = 'on_time';
                                
                                // Tạo đăng ký làm thêm giờ đã được duyệt
                                $overtimeRequests[] = [
                                    'user_id' => $user->id,
                                    'date' => $workDate,
                                    'start_time' => '17:00:00',
                                    'end_time' => $checkOut->format('H:i:s'),
                                    'hours' => round($overtimeHours + (rand(-10, 10) / 60), 2),
                                    'reason' => 'Hoàn thành công việc dự án',
                                    'status' => 'approved',
                                    'approved_by' => User::whereHas('roles', function($q) {
                                        $q->whereIn('name', ['admin', 'manager']);
                                    })->first()?->id,
                                    'approved_at' => Carbon::parse($workDate)->addDays(rand(0, 2)),
                                ];
                            }
                            
                            Attendance::updateOrCreate([
                                'user_id' => $user->id,
                                'work_date' => $workDate,
                            ], [
                                'check_in_at' => $checkIn,
                                'check_in_method' => 'manual',
                                'check_in_ip' => '127.0.0.1',
                                'check_out_at' => $checkOut,
                                'check_out_method' => 'manual',
                                'check_out_ip' => '127.0.0.1',
                                'status' => $status,
                            ]);
                            
                            $totalRecords++;
                        }
                    }
                    $current->addDay();
                }
            }
        }

        // Tạo đăng ký làm thêm giờ
        if (!empty($overtimeRequests)) {
            foreach ($overtimeRequests as $ot) {
                OvertimeRequest::updateOrCreate(
                    [
                        'user_id' => $ot['user_id'],
                        'date' => $ot['date'],
                    ],
                    $ot
                );
            }
            $this->command->info("Đã tạo " . count($overtimeRequests) . " đăng ký làm thêm giờ.");
        }

        $this->command->info("Đã tạo {$totalRecords} bản ghi chấm công!");
    }
}


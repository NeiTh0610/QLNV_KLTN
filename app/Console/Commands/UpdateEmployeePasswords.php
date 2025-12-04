<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UpdateEmployeePasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employees:update-passwords {password=123456}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật password cho tất cả tài khoản nhân viên (không bao gồm admin)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $newPassword = $this->argument('password');
        
        // Lấy tất cả users có role là 'employee' hoặc 'part_time' (không phải admin)
        $employees = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['employee', 'part_time']);
        })->get();

        if ($employees->isEmpty()) {
            $this->warn('Không tìm thấy nhân viên nào!');
            return Command::FAILURE;
        }

        $this->info("Đang cập nhật password cho {$employees->count()} nhân viên...");

        $updated = 0;
        foreach ($employees as $employee) {
            $employee->password = Hash::make($newPassword);
            $employee->save();
            $updated++;
            $this->line("✓ Đã cập nhật password cho: {$employee->name} ({$employee->email})");
        }

        $this->newLine();
        $this->info("✓ Hoàn thành! Đã cập nhật password cho {$updated} nhân viên.");
        $this->info("Password mới: {$newPassword}");

        return Command::SUCCESS;
    }
}

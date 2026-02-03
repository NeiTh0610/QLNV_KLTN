<?php

namespace App\Console\Commands;

use App\Models\PayrollRecord;
use App\Services\Payroll\PayrollCalculator;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RecalculatePayrollCommand extends Command
{
    protected $signature = 'payroll:recalculate {--all : Recalculate all payroll records}';

    protected $description = 'Recalculate all payroll records with updated formulas.';

    public function handle(PayrollCalculator $calculator): int
    {
        $this->info('Bắt đầu tính lại bảng lương...');

        if ($this->option('all')) {
            // Lấy tất cả các bảng lương unique theo period
            $periods = PayrollRecord::select('period_start', 'period_end')
                ->distinct()
                ->orderBy('period_start', 'asc')
                ->get();

            $this->info("Tìm thấy {$periods->count()} kỳ lương cần tính lại.");

            $bar = $this->output->createProgressBar($periods->count());
            $bar->start();

            foreach ($periods as $period) {
                $start = Carbon::parse($period->period_start);
                $end = Carbon::parse($period->period_end);
                
                // Xóa các bảng lương cũ của kỳ này
                PayrollRecord::where('period_start', $period->period_start)
                    ->where('period_end', $period->period_end)
                    ->delete();
                
                // Tính lại bảng lương
                $calculator->generate($start, $end);
                
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info('Đã tính lại tất cả bảng lương thành công!');
        } else {
            $this->warn('Vui lòng sử dụng --all để tính lại tất cả bảng lương.');
            $this->info('Ví dụ: php artisan payroll:recalculate --all');
        }

        return self::SUCCESS;
    }
}


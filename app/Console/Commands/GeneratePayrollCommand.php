<?php

namespace App\Console\Commands;

use App\Services\Payroll\PayrollCalculator;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GeneratePayrollCommand extends Command
{
    protected $signature = 'payroll:generate {--start=} {--end=}';

    protected $description = 'Generate payroll records for the specified period.';

    public function handle(PayrollCalculator $calculator): int
    {
        $start = $this->option('start')
            ? Carbon::parse($this->option('start'))->startOfDay()
            : now()->startOfMonth();

        $end = $this->option('end')
            ? Carbon::parse($this->option('end'))->endOfDay()
            : now()->endOfMonth();

        if ($end->lessThan($start)) {
            $this->error('End date must be after start date.');

            return self::FAILURE;
        }

        $records = $calculator->generate($start, $end);

        $this->info(sprintf(
            'Generated %d payroll record(s) for period %s to %s.',
            $records->count(),
            $start->toDateString(),
            $end->toDateString()
        ));

        return self::SUCCESS;
    }
}


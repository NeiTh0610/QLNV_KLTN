<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('payroll_records');

        Schema::create('payroll_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('working_days', 5, 2)->default(0);
            $table->decimal('hours_per_day', 5, 2)->nullable();
            $table->decimal('ot_hours', 5, 2)->default(0);
            $table->unsignedInteger('late_minutes')->default(0);
            $table->unsignedInteger('late_count_under_30')->default(0);
            $table->unsignedInteger('late_count_half_day')->default(0);
            $table->unsignedInteger('leave_minutes')->default(0);
            $table->unsignedInteger('early_leave_count_under_30')->default(0);
            $table->unsignedInteger('early_leave_count_half_day')->default(0);
            $table->decimal('deductions', 12, 2)->default(0);
            $table->decimal('allowances', 12, 2)->default(0);
            $table->decimal('gross_salary', 12, 2)->default(0);
            $table->decimal('social_insurance', 12, 2)->default(0);
            $table->decimal('health_insurance', 12, 2)->default(0);
            $table->decimal('unemployment_insurance', 12, 2)->default(0);
            $table->decimal('personal_income_tax', 12, 2)->default(0);
            $table->decimal('net_pay', 12, 2)->default(0);
            $table->enum('status', ['draft', 'confirmed', 'paid'])->default('draft');
            $table->timestamps();

            $table->unique(['user_id', 'period_start', 'period_end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_records');
    }
};

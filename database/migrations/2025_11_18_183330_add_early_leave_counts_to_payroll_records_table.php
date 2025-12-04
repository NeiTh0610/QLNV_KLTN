<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payroll_records', function (Blueprint $table) {
            $table->unsignedInteger('early_leave_count_under_30')->default(0)->after('leave_minutes');
            $table->unsignedInteger('early_leave_count_half_day')->default(0)->after('early_leave_count_under_30');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_records', function (Blueprint $table) {
            $table->dropColumn(['early_leave_count_under_30', 'early_leave_count_half_day']);
        });
    }
};

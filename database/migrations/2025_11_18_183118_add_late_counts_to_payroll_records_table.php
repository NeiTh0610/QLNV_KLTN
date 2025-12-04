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
            $table->unsignedInteger('late_count_under_30')->default(0)->after('late_minutes');
            $table->unsignedInteger('late_count_half_day')->default(0)->after('late_count_under_30');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_records', function (Blueprint $table) {
            $table->dropColumn(['late_count_under_30', 'late_count_half_day']);
        });
    }
};

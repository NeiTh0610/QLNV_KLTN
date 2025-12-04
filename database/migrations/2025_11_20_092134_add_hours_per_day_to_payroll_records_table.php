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
            $table->decimal('hours_per_day', 5, 2)->nullable()->after('working_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('payroll_records', 'hours_per_day')) {
            Schema::table('payroll_records', function (Blueprint $table) {
                $table->dropColumn('hours_per_day');
            });
        }
    }
};

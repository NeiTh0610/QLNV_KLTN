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
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->foreignId('salary_grade_id')
                ->nullable()
                ->after('department_id')
                ->constrained('salary_grades')
                ->nullOnDelete();

            $table->decimal('base_salary_override', 12, 2)
                ->nullable()
                ->after('salary_grade_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropForeign(['salary_grade_id']);
            $table->dropColumn(['salary_grade_id', 'base_salary_override']);
        });
    }
};


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
            // Kiểm tra và thêm các cột nếu chưa tồn tại
            if (!Schema::hasColumn('user_profiles', 'id_number')) {
                $table->string('id_number')->nullable()->after('position')->comment('CMND/CCCD');
            }
            if (!Schema::hasColumn('user_profiles', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('id_number')->comment('Ngày sinh');
            }
            if (!Schema::hasColumn('user_profiles', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth')->comment('Giới tính');
            }
            if (!Schema::hasColumn('user_profiles', 'address')) {
                $table->string('address')->nullable()->after('gender')->comment('Địa chỉ');
            }
            if (!Schema::hasColumn('user_profiles', 'personal_email')) {
                $table->string('personal_email')->nullable()->after('address')->comment('Email cá nhân');
            }
            if (!Schema::hasColumn('user_profiles', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('personal_email')->comment('Tên người liên hệ khẩn cấp');
            }
            if (!Schema::hasColumn('user_profiles', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name')->comment('SĐT người liên hệ khẩn cấp');
            }
            if (!Schema::hasColumn('user_profiles', 'education_level')) {
                $table->string('education_level')->nullable()->after('emergency_contact_phone')->comment('Trình độ học vấn');
            }
            if (!Schema::hasColumn('user_profiles', 'major')) {
                $table->string('major')->nullable()->after('education_level')->comment('Chuyên ngành');
            }
            if (!Schema::hasColumn('user_profiles', 'university')) {
                $table->string('university')->nullable()->after('major')->comment('Trường đại học');
            }
            if (!Schema::hasColumn('user_profiles', 'years_of_experience')) {
                $table->integer('years_of_experience')->default(0)->after('university')->comment('Số năm kinh nghiệm');
            }
            if (!Schema::hasColumn('user_profiles', 'skills')) {
                $table->text('skills')->nullable()->after('years_of_experience')->comment('Kỹ năng');
            }
            if (!Schema::hasColumn('user_profiles', 'certifications')) {
                $table->text('certifications')->nullable()->after('skills')->comment('Chứng chỉ');
            }
            if (!Schema::hasColumn('user_profiles', 'previous_work')) {
                $table->text('previous_work')->nullable()->after('certifications')->comment('Công việc trước đây');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'id_number',
                'date_of_birth',
                'gender',
                'address',
                'personal_email',
                'emergency_contact_name',
                'emergency_contact_phone',
                'education_level',
                'major',
                'university',
                'years_of_experience',
                'skills',
                'certifications',
                'previous_work',
            ]);
        });
    }
};

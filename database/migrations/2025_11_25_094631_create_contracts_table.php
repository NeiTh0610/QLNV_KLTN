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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('contract_number')->unique()->comment('Số hợp đồng');
            $table->enum('contract_type', ['full_time', 'part_time', 'intern', 'temporary'])->default('full_time')->comment('Loại hợp đồng');
            $table->date('start_date')->comment('Ngày bắt đầu');
            $table->date('end_date')->nullable()->comment('Ngày kết thúc (null nếu không xác định)');
            $table->decimal('salary', 15, 2)->comment('Mức lương');
            $table->string('position')->comment('Chức vụ');
            $table->text('job_description')->nullable()->comment('Mô tả công việc');
            $table->text('benefits')->nullable()->comment('Phúc lợi');
            $table->enum('status', ['active', 'expired', 'terminated', 'pending'])->default('active')->comment('Trạng thái');
            $table->date('signed_date')->nullable()->comment('Ngày ký');
            $table->text('notes')->nullable()->comment('Ghi chú');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->comment('Người tạo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};

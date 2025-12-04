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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('work_date');
            $table->dateTime('check_in_at')->nullable();
            $table->enum('check_in_method', ['manual', 'qr', 'mobile'])->nullable();
            $table->string('check_in_ip')->nullable();
            $table->foreignId('check_in_wifi_id')->nullable()->constrained('company_networks')->nullOnDelete();
            $table->dateTime('check_out_at')->nullable();
            $table->enum('check_out_method', ['manual', 'qr', 'mobile'])->nullable();
            $table->string('check_out_ip')->nullable();
            $table->foreignId('check_out_wifi_id')->nullable()->constrained('company_networks')->nullOnDelete();
            $table->enum('status', ['on_time', 'late', 'early_leave', 'absent', 'pending'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'work_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

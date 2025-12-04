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
        Schema::create('company_networks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ssid')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('ip_range')->nullable();
            $table->unsignedTinyInteger('priority')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_networks');
    }
};

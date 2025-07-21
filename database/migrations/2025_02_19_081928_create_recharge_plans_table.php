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
        Schema::create('recharge_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('provider_id');
            $table->json('states');
            $table->decimal('amount', 10, 2);
            $table->string('plan_name', 255);
            $table->string('validity');
            $table->enum('time_duration', ['days', 'months']);
            $table->enum('calling_options', ['Unlimited', 'Minutes']);
            $table->string('data', 50)->nullable();
            $table->enum('data_renewal', ['per day', 'per plan']);
            $table->enum('unlimited_5g', ['yes', 'no']);
            $table->integer('sms_count')->nullable();
            $table->enum('sms_renewal', ['per day', 'per plan']);
            $table->json('plan_category');
            $table->text('additional_benefits')->nullable();
            $table->timestamps();
            // Foreign Key Constraint (Assuming an 'operators' table exists)
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recharge_plans');
    }
};

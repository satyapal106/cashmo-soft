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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('provider_id');
            $table->unsignedBigInteger('slab_id')->nullable(); // Slab may not be needed for some types

            $table->enum('nature', ['cashback', 'charge']); // cashback or charge
            $table->enum('type', ['%', 'flat']); // % or flat
            $table->decimal('value', 10, 2)->default(0);

            $table->timestamps();

            // Foreign Keys
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
            $table->foreign('slab_id')->references('id')->on('slabs')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};

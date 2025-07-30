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
        Schema::create('wallet_balance_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('retailer_id'); 
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id')->nullable();
            $table->string('screenshot')->nullable(); 
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('retailer_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_balance_requests');
    }
};

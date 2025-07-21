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
        Schema::create('admin_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_wallet_id')->constrained('admin_wallets')->onDelete('cascade');
            $table->enum('type', ['credit', 'debit']);
            $table->string('transaction_id')->unique();
            $table->decimal('amount', 14, 2);
            $table->decimal('before_balance', 14, 2);
            $table->decimal('after_balance', 14, 2);
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled', 'refunded'])->default('success');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_wallet_transactions');
    }
};

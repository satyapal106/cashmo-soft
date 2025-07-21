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
        Schema::create('recharges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // client_id
            $table->string('number');
            $table->decimal('amount', 10, 2);
            $table->string('provider_id');
            $table->string('service_id');
            $table->string('status');
            $table->string('operator_ref')->nullable();
            $table->string('payid')->nullable(); // from mrspay
            $table->string('transaction_id')->unique(); // internal ID
            $table->string('cashmo_id')->nullable(); // optional custom
            $table->string('invoice_id')->unique(); // unique invoice

            $table->text('message')->nullable();
            $table->json('optional_fields')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recharges');
    }
};

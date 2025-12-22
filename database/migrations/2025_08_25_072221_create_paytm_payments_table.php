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
        Schema::create('paytm_payments', function (Blueprint $table) {
            $table->id();
            $table->string('identity');
            $table->string('order_id');
            $table->string('transaction_id')->nullable();
            $table->string('status')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('currency')->nullable();
            $table->string('response_code')->nullable();
            $table->string('response_message')->nullable();
            $table->json('response_payload')->nullable(); // Full Paytm response
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paytm_payments');
    }
};

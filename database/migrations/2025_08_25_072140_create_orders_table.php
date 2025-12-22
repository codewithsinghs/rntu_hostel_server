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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference_id')->unique(); // Format: ORD-yyyyMMdd-HHmmss-XXXX
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('guest_id')->nullable();
            $table->string('order_id')->unique(); // Format: ORD-yyyyMMdd-HHmmss-XXXX
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('payment_id')->nullable();
            $table->string('status')->default('pending');
            $table->string('message')->default('pending');
            $table->string('payment_method')->nullable(); // e.g., Paytm
            $table->string('purpose')->nullable(); // e.g. 'admission', 'accessory'
            $table->string('origin_url')->nullable(); // e.g. 'admission', 'accessory'
            $table->string('redirect_url')->nullable(); // e.g. 'admission', 'accessory'
            $table->string('callback_route')->nullable(); // e.g. 'admission.payment.callback'
            $table->json('metadata')->nullable(); // optional extra data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

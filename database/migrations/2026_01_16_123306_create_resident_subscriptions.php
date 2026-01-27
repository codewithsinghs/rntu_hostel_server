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
        Schema::create('resident_subscription', function (Blueprint $table) {
            $table->id();

            $table->foreignId('resident_id')->constrained()->cascadeOnDelete();

            // legacy traceability (used only for first creation)
            $table->foreignId('invoice_id')->nullable()->nullOnDelete();
            $table->foreignId('invoice_item_id')->nullable()->nullOnDelete();

            $table->string('service_code')->index();
            $table->string('service_name');

            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity')->default(1);

            $table->enum('billing_type', ['one_time', 'monthly']);
            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->enum('status', ['active', 'inactive', 'paused', 'suspended', 'cancelled'])->default('active');

            $table->timestamp('last_billed_at')->nullable();

            $table->timestamps();

            // 🔒 HARD DUPLICATE PROTECTION
            $table->unique(['invoice_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident_subscription');
    }
};

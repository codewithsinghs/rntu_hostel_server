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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();

            // Who is receiving refund
            $table->foreignId('resident_id')->constrained()->cascadeOnDelete();

            // Polymorphic relation
            $table->morphs('refundable');

            // Financial details
            $table->decimal('amount', 10, 2);
            $table->decimal('deduction_amount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2);

            $table->string('reason')->nullable();

            $table->enum('status', [
                'draft',
                'pending_approval',
                'approved',
                'processed',
                'rejected',
                'cancelled'
            ])->default('draft');

            $table->string('refund_mode')->nullable();
            $table->timestamp('txn_date')->nullable();
            $table->string('txn_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();

            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users');

            $table->json('payload')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};

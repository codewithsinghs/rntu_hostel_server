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
        Schema::create('checkout_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resident_id');
            $table->string('scholar_number')->nullable();
            $table->date('requested_exit_date');
            $table->text('description')->nullable();


            // refund related
            $table->boolean('refund_expected')->default(false);

            $table->string('account_holder')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();

            $table->enum('status', [
                'draft',
                'submitted',
                'in_clearance',
                'financial_review',
                // financial_settlement
                'payment_pending',
                'refund_pending',
                'ready_for_exit',
                'completed',
                'cancelled'
            ]);
            // $table->enum('financial_outcome', ['none', 'payable', 'refund', 'balanced']);


            $table->unsignedBigInteger('requested_by');
            $table->date('actual_exit_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            // $table->index('resident_id');
            // $table->index('status');
            // $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_requests');
    }
};

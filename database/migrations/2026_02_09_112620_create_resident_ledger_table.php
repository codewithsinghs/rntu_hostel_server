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
        Schema::create('resident_ledger', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resident_id');
            $table->string('source_type')->nullable();
            // $table->unsignedBigInteger('checkout_id')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();

            // $table->string('reference_type'); // invoice, payment, adjustment, refund
            // $table->unsignedBigInteger('reference_id')->nullable();

            $table->string('document_no')->nullable();
            $table->date('document_date')->nullable();

            $table->string('description')->nullable();

            $table->decimal('debit', 10, 2);
            $table->decimal('credit', 10, 2);

            $table->decimal('balance_after', 10, 2);

            $table->enum('type', [
                'rent',
                'electricity',
                'mess',
                'damage',
                'fine',
                'security_deposit',
                'deposit',
                'refund',
                'fee',
                'accessory',
                'service',
                'late_fee',
                'other'
            ]);

            // $table->decimal('amount', 10, 2);

            // $table->enum('direction', ['debit', 'credit']);
            $table->enum('status', ['open', 'settled', 'cancelled'])->default('open');

            $table->string('reference')->nullable();

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('approved_by')->nullable();

            $table->date('approved_at')->nullable();

            $table->text('narration')->nullable();

            $table->timestamps();

            // $table->index('resident_id');
            // $table->index(['source_type', 'source_id']);
            // $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident_ledger');
    }
};

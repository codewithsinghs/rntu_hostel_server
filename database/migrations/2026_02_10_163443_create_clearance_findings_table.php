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
        Schema::create('clearance_findings', function (Blueprint $table) {
            $table->id();
            $table->string('source_type'); // checkout 
            $table->unsignedBigInteger('source_id'); // checkout_id
            $table->string('category'); // damage, missing, subscription, overuse 
            $table->string('item')->nullable(); // bed, key, AC, mess 
            $table->decimal('amount', 10, 2)->default(0); 
            $table->text('remarks')->nullable(); 
            $table->enum('status', ['suggested', 'approved', 'rejected', 'cancelled'])->default('suggested'); 
            $table->unsignedBigInteger('created_by')->nullable();; 
            $table->unsignedBigInteger('approved_by')->nullable(); 
            $table->timestamps(); 
            
            // $table->index(['source_type', 'source_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clearance_findings');
    }
};

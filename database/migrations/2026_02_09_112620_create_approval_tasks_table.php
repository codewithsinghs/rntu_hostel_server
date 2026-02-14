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
        Schema::create('approval_tasks', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('checkout_id');
            // $table->string('taskable_type');
            // $table->unsignedBigInteger('taskable_id');
            $table->string('approvable_type');
            $table->unsignedBigInteger('approvable_id');

            $table->string('task_key', 50);
            $table->string('task_name');
            $table->string('department', 50);
            // $table->string('assigned_role', 50);
            $table->json('allowed_roles');
            $table->integer('sequence');

            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])
                ->default('pending');

            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            // $table->index(['taskable_type', 'taskable_id']);
            // $table->index(['approvable_type', 'approvable_id']);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_tasks');
    }
};

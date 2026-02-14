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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resident_id');   // FK to students table
            $table->string('room_number', 10)->nullable();
            $table->string('bed_number', 10)->nullable();
            $table->string('leave_type', 50)->default('general');
            $table->text('reason')->nullable();
            $table->date('start_date');
            $table->date('end_date');

            // Step 1: HOD approval
            $table->enum('hod_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('hod_remarks')->nullable();
            $table->timestamp('hod_approved_at')->nullable();

            // Step 2: Admin approval
            $table->enum('admin_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_remarks')->nullable();
            $table->timestamp('admin_approved_at')->nullable();

            // $table->string('token')->unique()->nullable();
            // Overall status (derived from HOD + Admin)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('resident_id')->references('id')->on('residents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};

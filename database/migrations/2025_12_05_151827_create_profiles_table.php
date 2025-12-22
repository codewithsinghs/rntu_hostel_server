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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            // Link to users table
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('resident_id')->nullable();
            // Personal Details
            $table->string('name')->nullable();
            $table->string('gender', 10)->nullable();
            $table->date('dob')->nullable();

            // Contact Information
            $table->string('mobile', 20)->nullable();
            $table->string('alternate_mobile', 20)->nullable();
            $table->string('email')->nullable();

            // Permanent Address
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('India');
            $table->string('pincode', 10)->nullable();

            // Parent / Guardian Details
            $table->string('father_name')->nullable();
            $table->string('father_mobile', 20)->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_mobile')->nullable();
            $table->string('parent_mobile')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_mobile', 20)->nullable();
            $table->string('guardian_relation')->nullable();

            // Emergency Contact
            $table->string('emergency_name')->nullable();
            $table->string('emergency_relation')->nullable();
            $table->string('emergency_mobile', 20)->nullable();

            // Identity & KYC Documents
            $table->string('aadhaar_number', 20)->nullable();
            $table->string('aadhaar_document')->nullable();   // File path
            $table->string('image')->nullable();              // Profile photo
            $table->string('signature')->nullable();

            // Academic Details
            $table->string('scholar_number')->nullable();
            $table->string('course')->nullable();
            $table->string('branch')->nullable();
            $table->string('semester')->nullable();
            $table->year('admission_year')->nullable();

            // Hostel Conditions Fields (as per requirement)
            $table->boolean('is_hosteler')->default(false);           // is resident/hosteler?
            $table->string('hostel_status')->default('active');       // active / left / suspended
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();

            // Additional Details
            $table->string('blood_group', 10)->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('remarks')->nullable();
            $table->json('others')->nullable(); // Full other info response
            // Soft deletes + timestamps
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};

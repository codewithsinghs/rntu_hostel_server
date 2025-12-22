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
        Schema::create('profile_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id');  // Reference to main profile
            $table->unsignedBigInteger('user_id');     // Resident user
            $table->unsignedBigInteger('resident_id')->nullable();

            // Store snapshot (JSON allows saving the whole structure)
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();

            // Optional (recommended)
            $table->unsignedBigInteger('updated_by')->nullable();  // Admin/staff who changed it
            $table->timestamp('changed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_histories');
    }
};

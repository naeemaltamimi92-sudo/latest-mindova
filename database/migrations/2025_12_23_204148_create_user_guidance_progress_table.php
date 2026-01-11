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
        Schema::create('user_guidance_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Guidance tracking
            $table->string('step_identifier'); // e.g., 'dashboard.welcome', 'challenges.browse'
            $table->string('page_identifier'); // e.g., 'dashboard', 'challenges.index'
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();

            // User progress stage
            $table->string('stage')->nullable(); // e.g., 'onboarding', 'active', 'contributing'

            // Metadata
            $table->json('metadata')->nullable(); // Additional context if needed

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'step_identifier']);
            $table->index(['user_id', 'page_identifier']);
            $table->unique(['user_id', 'step_identifier']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_guidance_progress');
    }
};

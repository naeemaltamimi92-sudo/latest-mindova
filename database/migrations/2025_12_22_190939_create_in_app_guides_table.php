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
        Schema::create('in_app_guides', function (Blueprint $table) {
            $table->id();
            $table->string('page_identifier')->unique(); // Route name or page identifier
            $table->string('page_title'); // Human-readable page name (e.g., "Submit Challenge")
            $table->text('what_is_this'); // What this page does
            $table->text('what_to_do'); // What the user should do here (bullet points as JSON)
            $table->text('next_step')->nullable(); // What happens next
            $table->json('ui_highlights')->nullable(); // UI elements to highlight (optional)
            $table->string('video_url')->nullable(); // Optional video tutorial
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        // User preferences for dismissed guides
        Schema::create('user_guide_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('page_identifier');
            $table->boolean('dismissed')->default(false);
            $table->timestamp('dismissed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'page_identifier']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_guide_preferences');
        Schema::dropIfExists('in_app_guides');
    }
};

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
        Schema::create('contextual_guidances', function (Blueprint $table) {
            $table->id();
            $table->string('page_identifier')->unique(); // Route name or page identifier
            $table->string('page_title')->nullable(); // Human-readable page name
            $table->text('guidance_text'); // The contextual guidance message (1-2 sentences)
            $table->string('icon')->default('💡'); // Emoji or icon to display
            $table->boolean('is_active')->default(true); // Enable/disable per page
            $table->integer('display_order')->default(0); // For future multi-guidance support
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contextual_guidances');
    }
};

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
        if (Schema::hasTable('user_guide_preferences')) {
            return; // Table already exists, skip creation
        }

        Schema::create('user_guide_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('page_identifier'); // e.g., 'dashboard', 'challenges.index', 'tasks.show'
            $table->boolean('guide_dismissed')->default(false);
            $table->timestamps();

            // Ensure one preference per user per page
            $table->unique(['user_id', 'page_identifier']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_guide_preferences');
    }
};

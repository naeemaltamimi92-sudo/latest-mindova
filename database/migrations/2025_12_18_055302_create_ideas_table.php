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
        Schema::create('ideas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->foreignId('volunteer_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->decimal('ai_quality_score', 5, 2)->nullable();
            $table->decimal('ai_relevance_score', 5, 2)->nullable();
            $table->decimal('ai_feasibility_score', 5, 2)->nullable();
            $table->text('ai_feedback')->nullable();
            $table->integer('community_votes_up')->default(0);
            $table->integer('community_votes_down')->default(0);
            $table->decimal('total_score', 8, 2)->default(0);
            $table->enum('status', ['pending_review', 'approved', 'implemented', 'rejected'])->default('pending_review');
            $table->timestamps();

            $table->index(['challenge_id', 'volunteer_id']);
            $table->index('total_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ideas');
    }
};

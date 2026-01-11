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
        Schema::create('ai_challenge_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('extracted_summary'); // AI-generated summary of insights
            $table->json('key_details')->nullable(); // Structured data: constraints, technical details, etc.
            $table->json('relevant_attachments')->nullable(); // References to specific attachments
            $table->string('insight_type')->default('general'); // general, task_specific, technical, constraint
            $table->integer('relevance_score')->nullable(); // 1-100, how relevant to task
            $table->timestamps();

            // Indexes for performance
            $table->index('challenge_id');
            $table->index('task_id');
            $table->index('insight_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_challenge_insights');
    }
};

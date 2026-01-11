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
        Schema::create('challenge_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->enum('stage', ['brief', 'complexity', 'decomposition']);
            $table->text('summary')->nullable();
            $table->json('objectives')->nullable();
            $table->json('constraints')->nullable();
            $table->json('assumptions')->nullable();
            $table->json('stakeholders')->nullable();
            $table->json('missing_information')->nullable();
            $table->json('success_criteria')->nullable();
            $table->integer('complexity_level')->nullable();
            $table->text('complexity_justification')->nullable();
            $table->text('risk_assessment')->nullable();
            $table->decimal('confidence_score', 5, 2)->nullable();
            $table->text('recommended_approach')->nullable();
            $table->decimal('estimated_effort_hours', 8, 2)->nullable();
            $table->enum('validation_status', ['pending', 'passed', 'failed', 'needs_review'])->default('pending');
            $table->json('validation_errors')->nullable();
            $table->boolean('requires_human_review')->default(false);
            $table->string('openai_model_used')->nullable();
            $table->integer('tokens_used')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_analyses');
    }
};

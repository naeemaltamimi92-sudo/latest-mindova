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
        Schema::create('openai_requests', function (Blueprint $table) {
            $table->id();
            $table->enum('request_type', ['cv_analysis', 'challenge_analysis', 'task_generation', 'idea_scoring', 'volunteer_matching', 'challenge_brief', 'complexity_evaluation', 'comment_scoring', 'work_submission_analysis']);
            $table->string('model');
            $table->text('prompt');
            $table->text('response')->nullable();
            $table->integer('tokens_prompt')->default(0);
            $table->integer('tokens_completion')->default(0);
            $table->integer('tokens_total')->default(0);
            $table->decimal('cost_usd', 10, 6)->default(0);
            $table->integer('duration_ms')->default(0);
            $table->enum('status', ['success', 'failed', 'rate_limited'])->default('success');
            $table->text('error_message')->nullable();
            $table->string('related_type')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->timestamp('created_at');

            $table->index(['request_type', 'created_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('openai_requests');
    }
};

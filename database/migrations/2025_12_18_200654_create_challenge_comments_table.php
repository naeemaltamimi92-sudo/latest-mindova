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
        Schema::create('challenge_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->integer('ai_score')->nullable()->comment('AI-generated quality score from 1-10');
            $table->enum('ai_score_status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->text('ai_analysis')->nullable()->comment('AI analysis of the comment');
            $table->timestamp('ai_scored_at')->nullable();
            $table->timestamps();

            $table->index(['challenge_id', 'ai_score']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_comments');
    }
};

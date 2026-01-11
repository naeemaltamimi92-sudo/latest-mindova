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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('original_description');
            $table->integer('complexity_level')->nullable();
            $table->enum('challenge_type', ['community_discussion', 'team_execution'])->nullable();
            $table->enum('status', ['submitted', 'analyzing', 'active', 'in_progress', 'completed', 'archived', 'rejected', 'delivered'])->default('submitted');
            $table->date('deadline')->nullable();
            $table->enum('ai_analysis_status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->timestamp('ai_analyzed_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'status', 'complexity_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};

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
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('cv_file_path')->nullable();
            $table->integer('availability_hours_per_week')->default(0);
            $table->enum('experience_level', ['Junior', 'Mid', 'Expert', 'Manager'])->nullable();
            $table->text('bio')->nullable();
            $table->json('education')->nullable();
            $table->json('work_experience')->nullable();
            $table->json('professional_domains')->nullable();
            $table->decimal('years_of_experience', 4, 1)->default(0);
            $table->decimal('reputation_score', 5, 2)->default(50.00);
            $table->integer('total_tasks_completed')->default(0);
            $table->decimal('total_hours_contributed', 8, 2)->default(0);
            $table->enum('ai_analysis_status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->decimal('ai_analysis_confidence', 5, 2)->nullable();
            $table->enum('validation_status', ['pending', 'passed', 'failed'])->default('pending');
            $table->json('validation_errors')->nullable();
            $table->boolean('skills_normalized')->default(false);
            $table->timestamp('ai_analyzed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};

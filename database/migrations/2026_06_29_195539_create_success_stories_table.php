<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('success_stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->cascadeOnDelete();
            $table->text('executive_summary');
            $table->text('initial_problem');
            $table->text('ai_analysis');
            $table->json('team_members');
            $table->text('solution_timeline');
            $table->text('final_implementation');
            $table->text('results_achieved');
            $table->text('business_impact');
            $table->text('company_testimonial');
            $table->text('lessons_learned');
            $table->unsignedInteger('total_verified_hours')->default(0);
            $table->unsignedInteger('reputation_points_awarded')->default(0);
            $table->boolean('is_demo')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('success_stories');
    }
};

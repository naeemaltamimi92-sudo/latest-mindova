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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workstream_id')->constrained()->onDelete('cascade');
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->json('required_skills');
            $table->enum('required_experience_level', ['Junior', 'Mid', 'Expert', 'Manager']);
            $table->text('expected_output');
            $table->json('acceptance_criteria');
            $table->decimal('estimated_hours', 6, 2)->default(0);
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['pending', 'open', 'assigned', 'in_progress', 'submitted', 'under_review', 'completed', 'rejected'])->default('pending');
            $table->integer('order')->default(0);
            $table->json('dependencies')->nullable();
            $table->enum('validation_status', ['pending', 'passed', 'failed', 'needs_regeneration'])->default('pending');
            $table->boolean('quality_check_passed')->default(false);
            $table->timestamps();

            $table->index(['workstream_id', 'challenge_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

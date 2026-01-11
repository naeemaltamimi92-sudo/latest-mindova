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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['forming', 'active', 'completed', 'disbanded'])->default('forming');
            $table->foreignId('leader_id')->nullable()->constrained('volunteers')->onDelete('set null');
            $table->json('objectives')->nullable(); // Team objectives
            $table->json('skills_coverage')->nullable(); // Skills covered by team
            $table->decimal('team_match_score', 5, 2)->nullable(); // Overall team fit score
            $table->integer('estimated_total_hours')->nullable();
            $table->timestamp('formation_completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};

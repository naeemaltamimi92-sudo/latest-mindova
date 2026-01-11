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
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('volunteer_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['leader', 'member', 'specialist'])->default('member');
            $table->enum('status', ['invited', 'accepted', 'declined', 'removed'])->default('invited');
            $table->text('role_description')->nullable(); // What this member is responsible for
            $table->json('assigned_skills')->nullable(); // Skills this member contributes
            $table->decimal('contribution_score', 5, 2)->nullable(); // Performance score
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->timestamps();

            // Ensure unique team-volunteer combination
            $table->unique(['team_id', 'volunteer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};

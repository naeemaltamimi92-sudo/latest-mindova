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
        Schema::create('task_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('volunteer_id')->constrained()->onDelete('cascade');
            $table->enum('invitation_status', ['invited', 'accepted', 'declined', 'auto_matched'])->default('invited');
            $table->decimal('ai_match_score', 5, 2)->nullable();
            $table->text('ai_match_reasoning')->nullable();
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('actual_hours', 6, 2)->nullable();
            $table->timestamps();

            $table->index(['task_id', 'volunteer_id', 'invitation_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_assignments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'task_decomposition' to the request_type enum
        DB::statement("ALTER TABLE openai_requests MODIFY COLUMN request_type ENUM(
            'cv_analysis',
            'challenge_analysis',
            'task_generation',
            'idea_scoring',
            'volunteer_matching',
            'challenge_brief',
            'complexity_evaluation',
            'comment_scoring',
            'work_submission_analysis',
            'task_decomposition'
        )");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'task_decomposition' from the request_type enum
        // Note: This will fail if there are any rows with 'task_decomposition' value
        DB::statement("ALTER TABLE openai_requests MODIFY COLUMN request_type ENUM(
            'cv_analysis',
            'challenge_analysis',
            'task_generation',
            'idea_scoring',
            'volunteer_matching',
            'challenge_brief',
            'complexity_evaluation',
            'comment_scoring',
            'work_submission_analysis'
        )");
    }
};

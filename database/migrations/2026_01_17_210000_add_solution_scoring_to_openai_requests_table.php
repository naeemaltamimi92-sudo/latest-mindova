<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
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
            'task_decomposition',
            'solution_scoring'
        )");
    }

    public function down(): void
    {
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
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Switch from a DB-level enum to a plain string so future request types
        // (e.g. solution_scoring, added later) don't require driver-specific DDL.
        // The original MySQL-only "MODIFY COLUMN ... ENUM(...)" statement broke
        // migrations on SQLite. Allowed values are enforced in application code.
        Schema::table('openai_requests', function (Blueprint $table) {
            $table->string('request_type')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This will fail if there are any rows with 'task_decomposition' value
        DB::table('openai_requests')->where('request_type', 'task_decomposition')->delete();

        Schema::table('openai_requests', function (Blueprint $table) {
            $table->enum('request_type', [
                'cv_analysis',
                'challenge_analysis',
                'task_generation',
                'idea_scoring',
                'volunteer_matching',
                'challenge_brief',
                'complexity_evaluation',
                'comment_scoring',
                'work_submission_analysis',
            ])->change();
        });
    }
};

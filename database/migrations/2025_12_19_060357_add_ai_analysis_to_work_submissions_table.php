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
        Schema::table('work_submissions', function (Blueprint $table) {
            $table->integer('ai_quality_score')->nullable()->after('status')->comment('AI quality score from 1-10');
            $table->enum('ai_analysis_status', ['pending', 'processing', 'completed', 'failed'])->default('pending')->after('ai_quality_score');
            $table->text('ai_feedback')->nullable()->after('ai_analysis_status')->comment('AI feedback on the solution');
            $table->boolean('solves_task')->nullable()->after('ai_feedback')->comment('Whether AI determined this solves the task');
            $table->timestamp('ai_analyzed_at')->nullable()->after('solves_task');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_submissions', function (Blueprint $table) {
            $table->dropColumn(['ai_quality_score', 'ai_analysis_status', 'ai_feedback', 'solves_task', 'ai_analyzed_at']);
        });
    }
};

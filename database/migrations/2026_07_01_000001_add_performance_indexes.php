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
        Schema::table('tasks', function (Blueprint $table) {
            // TaskWebController::available() filters solely by status (no
            // workstream_id/challenge_id prefix), so it can't use the
            // existing (workstream_id, challenge_id, status) composite.
            $table->index('status');
        });

        Schema::table('volunteers', function (Blueprint $table) {
            $table->index('reputation_score');
            $table->index('trust_score');
            $table->index('field');
            $table->index('experience_level');
            $table->index('general_nda_signed');
        });

        Schema::table('challenges', function (Blueprint $table) {
            // DashboardController's community-challenge query filters by
            // challenge_type + field + score together; the existing
            // (company_id, status, complexity_level) index doesn't cover it.
            $table->index(['challenge_type', 'field', 'score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropIndex(['reputation_score']);
            $table->dropIndex(['trust_score']);
            $table->dropIndex(['field']);
            $table->dropIndex(['experience_level']);
            $table->dropIndex(['general_nda_signed']);
        });

        Schema::table('challenges', function (Blueprint $table) {
            $table->dropIndex(['challenge_type', 'field', 'score']);
        });
    }
};

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
        // Switch status from a DB-level enum to a plain string so adding new
        // statuses doesn't require driver-specific DDL (the old MySQL-only
        // "MODIFY COLUMN ... ENUM(...)" statement broke migrations on SQLite).
        // Allowed values are enforced in application code (validation/services).
        Schema::table('ideas', function (Blueprint $table) {
            $table->string('status')->default('pending_review')->change();
        });

        Schema::table('ideas', function (Blueprint $table) {
            // Add new columns for scoring
            $table->decimal('ai_score', 5, 2)->nullable()->after('ai_feedback');
            $table->decimal('final_score', 8, 2)->nullable()->after('ai_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ideas', function (Blueprint $table) {
            $table->dropColumn(['ai_score', 'final_score']);
        });

        Schema::table('ideas', function (Blueprint $table) {
            $table->enum('status', ['pending_review', 'approved', 'implemented', 'rejected'])
                ->default('pending_review')
                ->change();
        });
    }
};

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
        // Modify status enum to add 'scored'
        DB::statement("ALTER TABLE ideas MODIFY COLUMN status ENUM('pending_review', 'approved', 'implemented', 'rejected', 'scored') DEFAULT 'pending_review'");

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

        // Revert status enum
        DB::statement("ALTER TABLE ideas MODIFY COLUMN status ENUM('pending_review', 'approved', 'implemented', 'rejected') DEFAULT 'pending_review'");
    }
};

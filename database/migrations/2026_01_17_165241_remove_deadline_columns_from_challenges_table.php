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
        // Drop deadline column if it exists
        if (Schema::hasColumn('challenges', 'deadline')) {
            Schema::table('challenges', function (Blueprint $table) {
                $table->dropColumn('deadline');
            });
        }

        // Drop submission_deadline column if it exists
        if (Schema::hasColumn('challenges', 'submission_deadline')) {
            Schema::table('challenges', function (Blueprint $table) {
                $table->dropColumn('submission_deadline');
            });
        }

        // Drop completion_deadline column if it exists
        if (Schema::hasColumn('challenges', 'completion_deadline')) {
            Schema::table('challenges', function (Blueprint $table) {
                $table->dropColumn('completion_deadline');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            $table->date('deadline')->nullable();
            $table->date('submission_deadline')->nullable();
            $table->date('completion_deadline')->nullable();
        });
    }
};

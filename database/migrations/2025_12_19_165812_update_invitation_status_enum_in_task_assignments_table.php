<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Switch from a DB-level enum to a plain string (the original MySQL-only
        // "MODIFY COLUMN ... ENUM(...)" statement broke migrations on SQLite).
        // Allowed values are enforced in application code.
        Schema::table('task_assignments', function (Blueprint $table) {
            $table->string('invitation_status')->default('invited')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear any data that might have the newer values before reverting
        DB::table('task_assignments')->whereIn('invitation_status', ['in_progress', 'submitted', 'completed'])->delete();

        Schema::table('task_assignments', function (Blueprint $table) {
            $table->enum('invitation_status', ['invited', 'accepted', 'declined', 'auto_matched'])
                ->default('invited')
                ->change();
        });
    }
};

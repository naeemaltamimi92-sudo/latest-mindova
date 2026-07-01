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
        // The original enum did not include 'matching', the status
        // MatchVolunteersToTasks assigns to a task once volunteers have been
        // invited (checked in TaskAssignmentController, TaskController, and
        // tasks/available.blade.php). Every write of 'matching' was silently
        // failing on a CHECK constraint violation and being swallowed by the
        // job's own try/catch, so tasks never left 'pending' and got
        // re-matched (and re-invited) on every subsequent matching run.
        // Switch to a plain string, same fix already applied to
        // task_assignments.invitation_status - MySQL-only "MODIFY COLUMN ...
        // ENUM(...)" breaks on SQLite. Allowed values stay enforced in code.
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear any data that might have the newer value before reverting
        DB::table('tasks')->where('status', 'matching')->update(['status' => 'pending']);

        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('status', ['pending', 'open', 'assigned', 'in_progress', 'submitted', 'under_review', 'completed', 'rejected'])
                ->default('pending')
                ->change();
        });
    }
};

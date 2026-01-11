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
        DB::statement("ALTER TABLE task_assignments MODIFY COLUMN invitation_status ENUM('invited', 'accepted', 'declined', 'auto_matched', 'in_progress', 'submitted', 'completed') DEFAULT 'invited'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear any data that might have the new enum values before reverting
        DB::table('task_assignments')->whereIn('invitation_status', ['in_progress', 'submitted', 'completed'])->delete();
        DB::statement("ALTER TABLE task_assignments MODIFY COLUMN invitation_status ENUM('invited', 'accepted', 'declined', 'auto_matched') DEFAULT 'invited'");
    }
};

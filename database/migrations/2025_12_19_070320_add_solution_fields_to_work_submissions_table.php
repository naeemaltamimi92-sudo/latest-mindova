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
            $table->foreignId('task_id')->nullable()->after('task_assignment_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('volunteer_id')->nullable()->after('task_id')->constrained('volunteers')->onDelete('cascade');
            $table->decimal('hours_worked', 5, 2)->nullable()->after('attachments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_submissions', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
            $table->dropForeign(['volunteer_id']);
            $table->dropColumn(['task_id', 'volunteer_id', 'hours_worked']);
        });
    }
};

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
        Schema::create('bug_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('issue_type', ['bug', 'ui_ux_issue', 'confusing_flow', 'something_didnt_work']);
            $table->text('description');
            $table->string('current_page'); // Auto-filled page URL/name
            $table->string('screenshot')->nullable(); // Optional screenshot path
            $table->boolean('blocked_user')->default(false); // Critical signal
            $table->string('user_agent')->nullable(); // Browser info
            $table->enum('status', ['new', 'reviewing', 'resolved', 'dismissed'])->default('new');
            $table->timestamps();

            $table->index(['status', 'blocked_user', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bug_reports');
    }
};

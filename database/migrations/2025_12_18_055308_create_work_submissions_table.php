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
        Schema::create('work_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_assignment_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->string('deliverable_url')->nullable();
            $table->json('attachments')->nullable();
            $table->enum('status', ['submitted', 'under_review', 'approved', 'rejected', 'revision_requested'])->default('submitted');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_submissions');
    }
};

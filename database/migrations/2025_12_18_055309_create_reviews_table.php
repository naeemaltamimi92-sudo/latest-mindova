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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_submission_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating')->nullable();
            $table->integer('quality_score')->nullable();
            $table->integer('timeliness_score')->nullable();
            $table->integer('communication_score')->nullable();
            $table->text('feedback')->nullable();
            $table->enum('decision', ['approved', 'rejected', 'revision_requested']);
            $table->text('revision_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

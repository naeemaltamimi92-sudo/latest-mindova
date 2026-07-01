<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            // Plain strings, not DB enums - MySQL-only ENUM() migration
            // statements break on SQLite (production's actual driver);
            // valid values are enforced in FormRequest validation instead.
            $table->string('type'); // problem | idea | feature_request
            $table->string('category')->nullable();
            $table->string('status')->default('open'); // open | under_review | planned | in_progress | done | declined
            $table->unsignedInteger('votes_count')->default(0);
            $table->foreignId('duplicate_of_id')->nullable()->constrained('feedback_items')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'status']);
            $table->index('votes_count');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_items');
    }
};

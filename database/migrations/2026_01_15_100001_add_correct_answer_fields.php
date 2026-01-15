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
        Schema::table('challenges', function (Blueprint $table) {
            $table->foreignId('correct_idea_id')->nullable()->after('status')->constrained('ideas')->nullOnDelete();
            $table->timestamp('closed_at')->nullable()->after('correct_idea_id');
        });

        Schema::table('ideas', function (Blueprint $table) {
            $table->boolean('is_correct_answer')->default(false)->after('status');
            $table->timestamp('marked_correct_at')->nullable()->after('is_correct_answer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            $table->dropForeign(['correct_idea_id']);
            $table->dropColumn(['correct_idea_id', 'closed_at']);
        });

        Schema::table('ideas', function (Blueprint $table) {
            $table->dropColumn(['is_correct_answer', 'marked_correct_at']);
        });
    }
};

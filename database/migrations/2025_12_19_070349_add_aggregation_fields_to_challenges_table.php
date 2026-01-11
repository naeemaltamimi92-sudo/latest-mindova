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
            $table->json('aggregated_solutions')->nullable()->after('ai_analyzed_at');
            $table->decimal('average_solution_quality', 5, 2)->nullable()->after('aggregated_solutions');
            $table->timestamp('completed_at')->nullable()->after('average_solution_quality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            $table->dropColumn(['aggregated_solutions', 'average_solution_quality', 'completed_at']);
        });
    }
};

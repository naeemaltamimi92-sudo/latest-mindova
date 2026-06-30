<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Round existing decimal values before changing column types
        DB::statement('UPDATE volunteers SET reputation_score = ROUND(reputation_score)');
        DB::statement('UPDATE reputation_history SET change_amount = ROUND(change_amount), new_total = ROUND(new_total)');

        Schema::table('volunteers', function (Blueprint $table) {
            // Expand reputation_score: decimal(5,2) max=999.99 → unsignedInteger for 1200+ stars
            $table->unsignedInteger('reputation_score')->default(0)->change();
            // Trust score: separate from stars, can go up/down (0–100 scale)
            $table->decimal('trust_score', 5, 1)->default(100.0)->after('reputation_score');
            // Flag set automatically when volunteer reaches Expert Candidate tier (500+ stars)
            $table->boolean('expert_available')->default(false)->after('trust_score');
        });

        Schema::table('reputation_history', function (Blueprint $table) {
            // change_amount: decimal(5,2) → integer (stars are always whole numbers)
            $table->integer('change_amount')->change();
            // new_total: decimal(5,2) max=999.99 → unsignedInteger
            $table->unsignedInteger('new_total')->change();
        });
    }

    public function down(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->decimal('reputation_score', 5, 2)->default(50.00)->change();
            $table->dropColumn(['trust_score', 'expert_available']);
        });

        Schema::table('reputation_history', function (Blueprint $table) {
            $table->decimal('change_amount', 5, 2)->change();
            $table->decimal('new_total', 5, 2)->change();
        });
    }
};

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
            // Add volunteer_id for volunteer-submitted challenges
            $table->foreignId('volunteer_id')->nullable()->after('company_id')->constrained()->onDelete('cascade');

            // Add source type to distinguish between company and volunteer challenges
            $table->enum('source_type', ['company', 'volunteer'])->default('company')->after('volunteer_id');

            // Index for volunteer challenges
            $table->index(['volunteer_id', 'status']);
            $table->index(['source_type', 'status']);
        });

        // Make company_id nullable (separate statement for MySQL compatibility)
        Schema::table('challenges', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            $table->dropIndex(['volunteer_id', 'status']);
            $table->dropIndex(['source_type', 'status']);
            $table->dropForeign(['volunteer_id']);
            $table->dropColumn(['volunteer_id', 'source_type']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Switch from a DB-level enum to a plain string (the original MySQL-only
        // "MODIFY COLUMN ... ENUM(...)" statement broke migrations on SQLite).
        // Allowed values are enforced in application code.
        Schema::table('challenges', function (Blueprint $table) {
            $table->string('status')->default('submitted')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('challenges')->where('status', 'closed')->update(['status' => 'archived']);

        Schema::table('challenges', function (Blueprint $table) {
            $table->enum('status', ['submitted', 'analyzing', 'active', 'in_progress', 'completed', 'archived', 'rejected', 'delivered'])
                ->default('submitted')
                ->change();
        });
    }
};

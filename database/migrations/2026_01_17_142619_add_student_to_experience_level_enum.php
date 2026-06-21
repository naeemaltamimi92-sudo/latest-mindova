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
        Schema::table('volunteers', function (Blueprint $table) {
            $table->string('experience_level')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear any records using the newer value before narrowing the enum back
        DB::table('volunteers')->where('experience_level', 'Student')->update(['experience_level' => null]);

        Schema::table('volunteers', function (Blueprint $table) {
            $table->enum('experience_level', ['Junior', 'Mid', 'Expert', 'Manager'])
                ->nullable()
                ->change();
        });
    }
};

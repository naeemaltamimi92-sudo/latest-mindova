<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Switch from a DB-level enum to a plain string (the original MySQL-only
        // "MODIFY ... ENUM(...)" statement broke migrations on SQLite).
        // Allowed values are enforced in application code.
        Schema::table('volunteers', function (Blueprint $table) {
            $table->string('validation_status')->default('pending')->change();
        });
    }

    public function down(): void
    {
        DB::table('volunteers')->where('validation_status', 'needs_review')->update(['validation_status' => 'pending']);

        Schema::table('volunteers', function (Blueprint $table) {
            $table->enum('validation_status', ['pending', 'passed', 'failed'])
                ->default('pending')
                ->change();
        });
    }
};

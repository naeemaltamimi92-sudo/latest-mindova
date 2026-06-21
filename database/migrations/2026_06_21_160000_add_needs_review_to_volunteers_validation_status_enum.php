<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE volunteers MODIFY validation_status ENUM('pending', 'passed', 'failed', 'needs_review') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE volunteers MODIFY validation_status ENUM('pending', 'passed', 'failed') NOT NULL DEFAULT 'pending'");
    }
};

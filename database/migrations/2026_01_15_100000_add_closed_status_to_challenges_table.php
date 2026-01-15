<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE challenges MODIFY COLUMN status ENUM('submitted', 'analyzing', 'active', 'in_progress', 'completed', 'archived', 'rejected', 'delivered', 'closed') DEFAULT 'submitted'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE challenges MODIFY COLUMN status ENUM('submitted', 'analyzing', 'active', 'in_progress', 'completed', 'archived', 'rejected', 'delivered') DEFAULT 'submitted'");
    }
};

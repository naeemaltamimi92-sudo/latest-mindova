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
        // Add 'Student' to the experience_level enum in volunteers table
        DB::statement("ALTER TABLE volunteers MODIFY COLUMN experience_level ENUM('Junior', 'Mid', 'Expert', 'Manager', 'Student') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This will fail if any records have 'Student' as their experience_level
        // You should update those records first before rolling back
        DB::statement("ALTER TABLE volunteers MODIFY COLUMN experience_level ENUM('Junior', 'Mid', 'Expert', 'Manager') NULL");
    }
};

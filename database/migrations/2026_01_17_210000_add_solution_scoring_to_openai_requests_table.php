<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // No-op: request_type was already converted to a plain, unconstrained
        // string column by the prior migration
        // (2026_01_14_120000_add_task_decomposition_to_openai_requests_table),
        // so adding 'solution_scoring' requires no schema change.
    }

    public function down(): void
    {
        // No-op: see up().
    }
};

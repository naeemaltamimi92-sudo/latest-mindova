<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // hiring_records is created inside create_hire_requests_table migration
    }

    public function down(): void
    {
        // dropped inside create_hire_requests_table migration
    }
};

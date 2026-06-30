<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // TINYINT UNSIGNED (max 255) causes a PDO overflow when a job is
        // retried more than 255 times, crashing the queue worker. Widen to
        // UNSIGNED SMALLINT (max 65,535) so it never overflows in practice.
        Schema::table('jobs', function (Blueprint $table) {
            $table->unsignedSmallInteger('attempts')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->unsignedTinyInteger('attempts')->default(0)->change();
        });
    }
};

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
        // Guard against narrowing back to TINYINT (max 255) if any row
        // accumulated more attempts than that while the wider column was
        // live - that's the exact overflow up() exists to prevent, so
        // silently truncating it back on rollback would reintroduce the bug.
        $overflowing = \DB::table('jobs')->where('attempts', '>', 255)->count();

        if ($overflowing > 0) {
            throw new \RuntimeException(
                "Cannot roll back: {$overflowing} job(s) have attempts > 255, which would overflow TINYINT UNSIGNED."
            );
        }

        Schema::table('jobs', function (Blueprint $table) {
            $table->unsignedTinyInteger('attempts')->default(0)->change();
        });
    }
};

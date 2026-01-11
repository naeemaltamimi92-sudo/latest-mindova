<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nda_agreements', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g., "Mindova Platform General NDA"
            $table->string('type')->default('general'); // general, challenge_specific
            $table->text('content'); // Full NDA text
            $table->string('version')->default('1.0'); // Track NDA versions
            $table->boolean('is_active')->default(true);
            $table->timestamp('effective_date')->nullable();
            $table->timestamps();

            $table->index(['type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nda_agreements');
    }
};

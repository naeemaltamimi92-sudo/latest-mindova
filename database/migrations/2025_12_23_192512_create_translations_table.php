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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 10)->index();
            $table->string('group', 100)->nullable()->index();
            $table->string('key', 500);
            $table->text('value');
            $table->timestamps();

            // Unique constraint on locale, group, and key
            $table->unique(['locale', 'group', 'key'], 'translations_locale_group_key_unique');

            // Index for searching
            $table->index(['locale', 'group']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};

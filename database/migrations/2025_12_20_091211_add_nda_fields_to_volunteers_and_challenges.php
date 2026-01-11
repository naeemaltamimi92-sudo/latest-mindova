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
        // Add NDA fields to volunteers table
        Schema::table('volunteers', function (Blueprint $table) {
            $table->boolean('general_nda_signed')->default(false)->after('validation_status');
            $table->timestamp('general_nda_signed_at')->nullable()->after('general_nda_signed');
            $table->string('general_nda_version')->nullable()->after('general_nda_signed_at');
        });

        // Add NDA fields to challenges table
        Schema::table('challenges', function (Blueprint $table) {
            $table->boolean('requires_nda')->default(true)->after('status');
            $table->text('nda_custom_terms')->nullable()->after('requires_nda');
            $table->enum('confidentiality_level', ['standard', 'high', 'critical'])->default('standard')->after('nda_custom_terms');
        });

        // Add NDA field to task_assignments table
        Schema::table('task_assignments', function (Blueprint $table) {
            $table->boolean('nda_signed')->default(false)->after('volunteer_id');
            $table->timestamp('nda_signed_at')->nullable()->after('nda_signed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropColumn(['general_nda_signed', 'general_nda_signed_at', 'general_nda_version']);
        });

        Schema::table('challenges', function (Blueprint $table) {
            $table->dropColumn(['requires_nda', 'nda_custom_terms', 'confidentiality_level']);
        });

        Schema::table('task_assignments', function (Blueprint $table) {
            $table->dropColumn(['nda_signed', 'nda_signed_at']);
        });
    }
};

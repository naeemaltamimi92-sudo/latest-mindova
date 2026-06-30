<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expert_challenge_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->foreignId('volunteer_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['lead_expert', 'domain_expert', 'quality_reviewer'])
                  ->default('domain_expert');
            $table->enum('status', ['invited', 'accepted', 'declined', 'active', 'completed', 'withdrawn'])
                  ->default('invited');
            $table->decimal('selection_score', 6, 2)->default(0);
            $table->text('selection_reasoning')->nullable();
            // Expert's final sign-off on the project before certificate issuance
            $table->boolean('final_approved')->default(false);
            $table->timestamp('final_approved_at')->nullable();
            $table->text('final_approval_notes')->nullable();
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->unique(['challenge_id', 'volunteer_id']);
            $table->index(['challenge_id', 'status']);
            $table->index(['volunteer_id', 'status']);
        });

        // Add expert-mode flag to challenges
        Schema::table('challenges', function (Blueprint $table) {
            $table->boolean('expert_mode')->default(false)->after('visibility');
            $table->timestamp('expert_assigned_at')->nullable()->after('expert_mode');
        });

        // Enhance certificates with all spec-required fields
        Schema::table('certificates', function (Blueprint $table) {
            // Expert who approved this certificate
            $table->foreignId('expert_id')->nullable()->constrained('volunteers')->onDelete('set null')->after('company_id');
            $table->timestamp('expert_approved_at')->nullable()->after('expert_id');
            // Project timeline
            $table->date('project_start_date')->nullable()->after('issued_at');
            $table->date('project_end_date')->nullable()->after('project_start_date');
            // Industry sector from challenge/company
            $table->string('industry')->nullable()->after('project_end_date');
            // Technologies / disciplines (JSON array)
            $table->json('technologies')->nullable()->after('industry');
            // Tamper-proof SHA-256 hash of key certificate fields
            $table->string('verification_hash', 64)->nullable()->after('technologies');
            // Company can choose to show or hide their brand
            $table->boolean('show_company_name')->default(true)->after('verification_hash');
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn(['expert_id', 'expert_approved_at', 'project_start_date',
                'project_end_date', 'industry', 'technologies', 'verification_hash', 'show_company_name']);
        });

        Schema::table('challenges', function (Blueprint $table) {
            $table->dropColumn(['expert_mode', 'expert_assigned_at']);
        });

        Schema::dropIfExists('expert_challenge_assignments');
    }
};

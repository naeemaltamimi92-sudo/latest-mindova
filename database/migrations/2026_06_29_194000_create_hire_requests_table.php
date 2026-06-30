<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hire_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('volunteer_id')->constrained('volunteers')->cascadeOnDelete();
            $table->foreignId('agency_portal_id')->nullable()->constrained('agency_portals')->nullOnDelete();
            $table->enum('type', ['full_time', 'part_time', 'consulting', 'project', 'invitation'])->default('project');
            $table->enum('status', ['pending', 'accepted', 'declined', 'withdrawn', 'converted'])->default('pending');
            $table->string('position_title');
            $table->text('message');
            $table->string('salary_range')->nullable();
            $table->date('proposed_start_date')->nullable();
            $table->boolean('is_private_challenge')->default(false);
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index(['volunteer_id', 'status']);
            $table->index(['company_user_id', 'status']);
        });

        Schema::create('hiring_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hire_request_id')->nullable()->constrained('hire_requests')->nullOnDelete();
            $table->foreignId('company_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('volunteer_id')->constrained('volunteers')->cascadeOnDelete();
            $table->string('position_title');
            $table->enum('engagement_type', ['full_time', 'part_time', 'consulting', 'project'])->default('project');
            $table->timestamp('hired_at');
            $table->string('hiring_verification_id', 30)->unique();
            $table->json('skills_used')->nullable();
            $table->json('verified_certificate_ids')->nullable();
            $table->string('professional_level');
            $table->unsignedInteger('reputation_stars_at_hire')->default(0);
            $table->decimal('trust_score_at_hire', 5, 1)->default(100.0);
            $table->enum('status', ['active', 'ended', 'cancelled'])->default('active');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            $table->index(['volunteer_id', 'status']);
            $table->index(['company_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hiring_records');
        Schema::dropIfExists('hire_requests');
    }
};

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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Volunteer
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained('users')->onDelete('set null'); // Company user

            // Certificate Details
            $table->string('certificate_number')->unique(); // Unique certificate ID
            $table->enum('certificate_type', ['participation', 'completion'])->default('participation');
            $table->string('role'); // Volunteer's role in the challenge

            // Contribution Summary (AI-generated + system data)
            $table->text('contribution_summary'); // Short description of value added
            $table->json('contribution_types')->nullable(); // Array of contribution types

            // Time Commitment
            $table->decimal('total_hours', 8, 2)->default(0); // Total time spent
            $table->json('time_breakdown')->nullable(); // Analysis, Execution, Review hours

            // Company Confirmation
            $table->boolean('company_confirmed')->default(false);
            $table->timestamp('confirmed_at')->nullable();
            $table->string('company_logo_path')->nullable(); // Company logo/stamp

            // Certificate File
            $table->string('pdf_path')->nullable(); // Path to generated PDF

            // Metadata
            $table->timestamp('issued_at')->nullable();
            $table->boolean('is_revoked')->default(false);
            $table->timestamp('revoked_at')->nullable();
            $table->text('revocation_reason')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'challenge_id']);
            $table->index('certificate_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};

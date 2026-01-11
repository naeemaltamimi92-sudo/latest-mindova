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
        Schema::table('challenges', function (Blueprint $table) {
            // Admin management fields
            $table->text('admin_notes')->nullable()->after('rejection_reason');
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('admin_notes');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->boolean('is_featured')->default(false)->after('reviewed_at');
            $table->boolean('is_pinned')->default(false)->after('is_featured');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal')->after('is_pinned');
            $table->enum('visibility', ['public', 'private', 'unlisted'])->default('public')->after('priority');

            // Enhanced tracking
            $table->integer('view_count')->default(0)->after('visibility');
            $table->integer('application_count')->default(0)->after('view_count');
            $table->timestamp('last_activity_at')->nullable()->after('application_count');

            // Budget and timeline
            $table->decimal('estimated_budget', 12, 2)->nullable()->after('last_activity_at');
            $table->decimal('actual_budget', 12, 2)->nullable()->after('estimated_budget');
            $table->string('currency', 3)->default('SAR')->after('actual_budget');

            // Additional metadata
            $table->json('tags')->nullable()->after('currency');
            $table->string('external_reference')->nullable()->after('tags');
            $table->text('internal_notes')->nullable()->after('external_reference');

            // Indexes
            $table->index('is_featured');
            $table->index('is_pinned');
            $table->index('priority');
            $table->index('visibility');
            $table->index('last_activity_at');
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
        });

        // Create challenge status history table
        Schema::create('challenge_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->cascadeOnDelete();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->text('reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['challenge_id', 'created_at']);
        });

        // Create challenge admin actions log
        Schema::create('challenge_admin_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->string('action'); // viewed, edited, status_changed, deleted, restored, etc.
            $table->text('description')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['challenge_id', 'created_at']);
            $table->index(['admin_id', 'created_at']);
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_admin_logs');
        Schema::dropIfExists('challenge_status_history');

        Schema::table('challenges', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['is_pinned']);
            $table->dropIndex(['priority']);
            $table->dropIndex(['visibility']);
            $table->dropIndex(['last_activity_at']);

            $table->dropColumn([
                'admin_notes',
                'reviewed_by',
                'reviewed_at',
                'is_featured',
                'is_pinned',
                'priority',
                'visibility',
                'view_count',
                'application_count',
                'last_activity_at',
                'estimated_budget',
                'actual_budget',
                'currency',
                'tags',
                'external_reference',
                'internal_notes',
            ]);
        });
    }
};

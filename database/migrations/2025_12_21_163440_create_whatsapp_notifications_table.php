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
        Schema::create('whatsapp_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['team_invite', 'task_assigned', 'critical_update']);
            $table->string('entity_type'); // 'team', 'task', 'challenge'
            $table->unsignedBigInteger('entity_id');
            $table->string('template_name');
            $table->enum('status', ['queued', 'sent', 'failed', 'skipped'])->default('queued');
            $table->string('provider_message_id')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            // Prevent duplicate notifications
            $table->unique(['user_id', 'type', 'entity_type', 'entity_id'], 'whatsapp_notification_unique');

            // Index for queries
            $table->index(['user_id', 'status', 'created_at']);
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_notifications');
    }
};

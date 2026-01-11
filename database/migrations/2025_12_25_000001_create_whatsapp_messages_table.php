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
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // WhatsApp Message Identifiers
            $table->string('whatsapp_message_id')->nullable()->index();
            $table->string('phone_number', 20)->index();
            $table->string('contact_name')->nullable();

            // Message Details
            $table->enum('direction', ['incoming', 'outgoing'])->default('outgoing');
            $table->string('type', 50)->default('text'); // text, template, image, document, interactive, etc.
            $table->string('template_name')->nullable();
            $table->text('content')->nullable();

            // Full Payload (for debugging and reference)
            $table->json('payload')->nullable();
            $table->json('response')->nullable();

            // Status Tracking
            $table->string('status', 30)->default('pending');
            // pending, sent, delivered, read, failed, received

            $table->text('error_message')->nullable();

            // Timestamps
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('status_updated_at')->nullable();
            $table->timestamps();

            // Indexes for common queries
            $table->index(['user_id', 'direction']);
            $table->index(['phone_number', 'direction']);
            $table->index(['status', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};

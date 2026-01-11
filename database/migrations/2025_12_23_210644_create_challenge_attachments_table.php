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
        Schema::create('challenge_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type'); // pdf, docx, pptx, jpg, etc.
            $table->string('mime_type');
            $table->integer('file_size'); // in bytes
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->text('extracted_text')->nullable(); // Text extracted from file for AI analysis
            $table->boolean('processed')->default(false); // Whether AI has processed this file
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index('challenge_id');
            $table->index('uploaded_by');
            $table->index('processed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_attachments');
    }
};

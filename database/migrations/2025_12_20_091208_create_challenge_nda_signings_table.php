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
        Schema::create('challenge_nda_signings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->foreignId('nda_agreement_id')->constrained()->onDelete('cascade');
            $table->string('signer_name');
            $table->string('signer_email');
            $table->string('ip_address');
            $table->text('user_agent')->nullable();
            $table->timestamp('signed_at');
            $table->string('signature_hash'); // Hash of signature for verification
            $table->boolean('is_valid')->default(true);
            $table->timestamp('revoked_at')->nullable();
            $table->text('revocation_reason')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'challenge_id']);
            $table->index(['challenge_id', 'is_valid']);
            $table->index('signed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_nda_signings');
    }
};

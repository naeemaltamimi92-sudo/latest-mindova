<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agency_portals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('agency_name');
            $table->string('slug')->unique();
            $table->string('logo_path')->nullable();
            $table->string('primary_color', 7)->default('#6366f1');
            $table->string('secondary_color', 7)->default('#8b5cf6');
            $table->string('custom_domain')->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('talent_slots')->default(500);
            $table->unsignedInteger('client_slots')->default(50);
            $table->text('description')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agency_portals');
    }
};

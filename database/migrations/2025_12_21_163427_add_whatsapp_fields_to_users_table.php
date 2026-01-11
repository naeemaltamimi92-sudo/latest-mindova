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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('whatsapp_opt_in')->default(false)->after('email');
            $table->string('whatsapp_number', 20)->nullable()->after('whatsapp_opt_in');
            $table->timestamp('whatsapp_opted_in_at')->nullable()->after('whatsapp_number');
            $table->timestamp('whatsapp_opted_out_at')->nullable()->after('whatsapp_opted_in_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp_opt_in',
                'whatsapp_number',
                'whatsapp_opted_in_at',
                'whatsapp_opted_out_at',
            ]);
        });
    }
};

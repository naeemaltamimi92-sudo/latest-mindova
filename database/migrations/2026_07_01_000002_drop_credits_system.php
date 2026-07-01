<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('credit_transactions');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'credits_balance')) {
                $table->dropColumn('credits_balance');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('credits_balance')->default(0)->after('locale');
        });

        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('amount');
            $table->unsignedInteger('balance_after');
            $table->enum('type', ['purchase', 'earned', 'spent', 'awarded', 'gifted', 'refunded']);
            $table->string('description');
            $table->string('related_type')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->timestamp('created_at');

            $table->index(['user_id', 'created_at']);
        });
    }
};

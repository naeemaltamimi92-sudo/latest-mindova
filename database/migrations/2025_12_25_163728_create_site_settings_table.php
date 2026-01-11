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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->string('group')->default('general'); // general, features, appearance, etc.
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false); // Can be accessed without auth
            $table->timestamps();
        });

        // Insert default settings
        $this->seedDefaultSettings();
    }

    /**
     * Seed default settings
     */
    private function seedDefaultSettings(): void
    {
        $settings = [
            [
                'key' => 'language_switcher_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'features',
                'label' => 'Language Switcher',
                'description' => 'Enable or disable the language switcher for all users',
                'is_public' => true,
            ],
            [
                'key' => 'default_language',
                'value' => 'en',
                'type' => 'string',
                'group' => 'features',
                'label' => 'Default Language',
                'description' => 'Default language for the platform',
                'is_public' => true,
            ],
            [
                'key' => 'rtl_support_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'features',
                'label' => 'RTL Support',
                'description' => 'Enable right-to-left language support (Arabic)',
                'is_public' => true,
            ],
            [
                'key' => 'whatsapp_notifications_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'features',
                'label' => 'WhatsApp Notifications',
                'description' => 'Enable WhatsApp notification features for users',
                'is_public' => true,
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'general',
                'label' => 'Maintenance Mode',
                'description' => 'Put the site in maintenance mode',
                'is_public' => false,
            ],
            [
                'key' => 'registration_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'features',
                'label' => 'User Registration',
                'description' => 'Allow new users to register',
                'is_public' => true,
            ],
        ];

        foreach ($settings as $setting) {
            \DB::table('site_settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};

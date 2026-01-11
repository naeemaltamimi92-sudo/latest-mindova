<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Mindova Owner/Admin account
        $admin = User::firstOrCreate(
            ['email' => 'mindova.ai@gmail.com'],
            [
                'name' => 'Mindova Owner',
                'password' => Hash::make('MindovaAdmin2025!@'),
                'user_type' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'locale' => 'en',
            ]
        );

        if ($admin->wasRecentlyCreated) {
            $this->command->info('✅ Admin user created: mindova.ai@gmail.com');
            $this->command->warn('⚠️  Default password: MindovaAdmin2025!');
            $this->command->warn('⚠️  IMPORTANT: Please change this password immediately after first login!');
        } else {
            $this->command->info('ℹ️  Admin user already exists: mindova.ai@gmail.com');
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Only seeds the admin user for production-ready platform.
     * All other data should be created through the platform's UI.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,  // Creates the platform admin account
        ]);

        $this->command->info('');
        $this->command->info('🎉 Database seeding complete!');
        $this->command->info('');
        $this->command->info('The platform is ready for real data.');
        $this->command->info('- Companies and Volunteers can register through the platform');
        $this->command->info('- Challenges will be created by companies');
        $this->command->info('- All workflows are production-ready');
        $this->command->info('');
    }
}

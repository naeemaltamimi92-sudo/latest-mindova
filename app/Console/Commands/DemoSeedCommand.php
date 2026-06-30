<?php

namespace App\Console\Commands;

use App\Services\Demo\DemoDataService;
use Illuminate\Console\Command;

class DemoSeedCommand extends Command
{
    protected $signature = 'demo:seed {--fresh : Clear all existing demo data before seeding}';
    protected $description = 'Seed the database with realistic demo data to showcase Mindova\'s full workflow';

    public function handle(DemoDataService $service): int
    {
        if ($this->option('fresh')) {
            $this->info('Clearing existing demo data...');
            $cleared = $service->clear();
            $this->line("  Removed $cleared demo user accounts and all related records.");
        }

        // Check if demo data already exists
        if (\App\Models\User::where('is_demo', true)->exists() && !$this->option('fresh')) {
            $this->warn('Demo data already exists. Run with --fresh to replace it.');
            return self::FAILURE;
        }

        $this->info('Seeding Mindova demo ecosystem...');
        $this->newLine();

        $steps = [
            '  Creating 10 demo professionals across 5 industries...',
            '  Creating 5 demo company accounts...',
            '  Creating 5 demo challenges with full lifecycle simulation...',
        ];
        foreach ($steps as $step) {
            $this->line($step);
        }

        $this->newLine();

        $summary = $service->seed();

        $this->info('Demo ecosystem seeded successfully!');
        $this->newLine();
        $this->line('<fg=green>Challenges created:</>');
        foreach ($summary as $item) {
            $this->line("  ✓ $item");
        }

        $this->newLine();
        $this->line('<fg=yellow>Demo account credentials (all accounts):</>');
        $this->line('  Password: Demo@2024!');
        $this->newLine();
        $this->line('<fg=cyan>Sample professional emails:</>');
        $this->line('  ahmed.demo@mindova.test    (Manufacturing Expert, 520 stars)');
        $this->line('  marco.demo@mindova.test    (Chemical Engineer, 840 stars)');
        $this->line('  sarah.demo@mindova.test    (Data Scientist, 310 stars)');
        $this->line('  emma.demo@mindova.test     (Healthcare Expert, 615 stars)');
        $this->newLine();
        $this->line('<fg=cyan>Sample company emails:</>');
        $this->line('  alnoor.demo@mindova.test   (Al-Noor Manufacturing)');
        $this->line('  techflow.demo@mindova.test (TechFlow Solutions)');
        $this->line('  gci.demo@mindova.test      (Gulf Chemical Industries)');

        return self::SUCCESS;
    }
}

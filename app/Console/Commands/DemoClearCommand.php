<?php

namespace App\Console\Commands;

use App\Services\Demo\DemoDataService;
use Illuminate\Console\Command;

class DemoClearCommand extends Command
{
    protected $signature = 'demo:clear';
    protected $description = 'Remove all demo data from the database (users, challenges, tasks, certificates, success stories)';

    public function handle(DemoDataService $service): int
    {
        if (!$this->confirm('This will permanently delete all demo data. Continue?', false)) {
            $this->info('Aborted.');
            return self::SUCCESS;
        }

        $this->info('Clearing demo data...');

        $count = $service->clear();

        if ($count === 0) {
            $this->warn('No demo data found.');
        } else {
            $this->info("Removed $count demo user accounts and all related records.");
        }

        return self::SUCCESS;
    }
}

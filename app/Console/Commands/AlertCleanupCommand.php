<?php

namespace App\Console\Commands;

use App\Services\AlertService;
use Illuminate\Console\Command;

class AlertCleanupCommand extends Command
{
    protected $signature = 'alerts:cleanup';
    protected $description = 'Clean up old read alerts (older than 30 days)';

    public function handle()
    {
        $this->info('Cleaning up old alerts...');
        
        $alertService = new AlertService();
        $alertService->cleanupOldAlerts();
        
        $this->info('Old alerts cleaned up successfully!');
        
        return 0;
    }
}

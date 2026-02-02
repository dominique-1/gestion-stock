<?php

namespace App\Console\Commands;

use App\Services\AlertService;
use Illuminate\Console\Command;

class AlertEmailCommand extends Command
{
    protected $signature = 'alerts:email {--force}';
    protected $description = 'Send email alerts for low stock, overstock, and expiring products';

    public function handle()
    {
        $this->info('Sending email alerts...');
        
        $alertService = new AlertService();
        $alertService->generateAllAlerts();
        
        $stats = $alertService->getAlertStats();
        
        $this->info('Email alerts sent successfully!');
        $this->info('Total alerts: ' . $stats['total']);
        $this->info('Unread alerts: ' . $stats['unread']);
        $this->info('Critical alerts: ' . $stats['critical']);
        
        return 0;
    }
}

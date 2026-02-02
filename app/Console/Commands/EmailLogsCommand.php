<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class EmailLogsCommand extends Command
{
    protected $signature = 'email:logs {--limit=10}';
    protected $description = 'Show recent email logs';

    public function handle()
    {
        $limit = $this->option('limit');
        $logFile = storage_path('logs/laravel.log');
        
        if (!File::exists($logFile)) {
            $this->error('Log file not found');
            return 1;
        }
        
        $this->info("📧 Recent Email Logs (last {$limit} entries):");
        $this->line(str_repeat('=', 60));
        
        $content = File::get($logFile);
        $lines = array_reverse(explode("\n", $content));
        
        $emailLogs = [];
        $captureNext = false;
        
        foreach ($lines as $line) {
            if (strpos($line, 'Alert email sent') !== false || strpos($line, 'Failed to send alert email') !== false) {
                $emailLogs[] = $line;
                if (count($emailLogs) >= $limit) break;
            }
        }
        
        if (empty($emailLogs)) {
            $this->info('No email logs found');
        } else {
            foreach (array_reverse($emailLogs) as $log) {
                if (strpos($log, 'Alert email sent') !== false) {
                    $this->info("✅ " . $log);
                } else {
                    $this->error("❌ " . $log);
                }
            }
        }
        
        $this->line(str_repeat('=', 60));
        $this->info('💡 Tips:');
        $this->info('   - Check alerts page for email status');
        $this->info('   - Use "php artisan email:test" to test email sending');
        
        return 0;
    }
}

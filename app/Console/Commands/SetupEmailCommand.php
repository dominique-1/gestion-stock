<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupEmailCommand extends Command
{
    protected $signature = 'email:setup {driver=log}';
    protected $description = 'Setup email configuration (log, smtp, mailpit)';

    public function handle()
    {
        $driver = $this->argument('driver');
        
        $this->info("🔧 Setting up email driver: {$driver}");
        
        $envFile = base_path('.env');
        $envExample = base_path('.env.example');
        
        if (!File::exists($envFile)) {
            if (File::exists($envExample)) {
                File::copy($envExample, $envFile);
                $this->info("✅ Created .env from .env.example");
            } else {
                $this->error("❌ No .env or .env.example found");
                return 1;
            }
        }
        
        $content = File::get($envFile);
        
        // Update mail configuration
        $configs = [
            'log' => [
                'MAIL_MAILER=log',
                'MAIL_HOST=',
                'MAIL_PORT=',
                'MAIL_USERNAME=',
                'MAIL_PASSWORD=',
                'MAIL_ENCRYPTION=',
                'MAIL_FROM_ADDRESS=admin@example.com'
            ],
            'smtp' => [
                'MAIL_MAILER=smtp',
                'MAIL_HOST=mailpit',
                'MAIL_PORT=1025',
                'MAIL_USERNAME=',
                'MAIL_PASSWORD=',
                'MAIL_ENCRYPTION=',
                'MAIL_FROM_ADDRESS=admin@example.com'
            ]
        ];
        
        if (!isset($configs[$driver])) {
            $this->error("❌ Invalid driver. Use: log, smtp");
            return 1;
        }
        
        foreach ($configs[$driver] as $config) {
            $key = explode('=', $config)[0];
            $value = explode('=', $config)[1];
            
            if (strpos($content, $key) !== false) {
                $content = preg_replace("/^{$key}=.*/m", $config, $content);
            } else {
                $content .= "\n{$config}";
            }
        }
        
        File::put($envFile, $content);
        
        // Clear config cache
        $this->call('config:clear');
        
        $this->info("✅ Email configuration updated!");
        $this->info("📧 Driver: {$driver}");
        
        if ($driver === 'log') {
            $this->info("📝 Emails will be saved to logs");
            $this->info("🔍 Check with: php artisan email:logs");
        } elseif ($driver === 'smtp') {
            $this->info("🌐 Using Mailpit (requires installation)");
            $this->info("📥 Install with: go install github.com/axllent/mailpit@latest");
        }
        
        return 0;
    }
}

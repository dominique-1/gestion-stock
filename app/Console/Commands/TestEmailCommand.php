<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    protected $signature = 'email:test {email=test@example.com}';
    protected $description = 'Test email sending configuration';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Testing email to: {$email}");
        $this->info("Mail driver: " . config('mail.default'));
        $this->info("Mail host: " . config('mail.mailers.smtp.host'));
        $this->info("Mail port: " . config('mail.mailers.smtp.port'));
        
        try {
            Mail::raw('This is a test email from Laravel', function ($message) use ($email) {
                $message->to($email)
                    ->subject('Test Email - ' . now()->format('Y-m-d H:i:s'));
            });
            
            $this->info('✅ Email sent successfully!');
            
            if (config('mail.default') === 'smtp' && config('mail.mailers.smtp.host') === 'mailpit') {
                $this->info('📧 Check Mailpit at: http://localhost:1025');
                $this->info('🌐 Or at: http://localhost:8025 (web interface)');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Email failed: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}

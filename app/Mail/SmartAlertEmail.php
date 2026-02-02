<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SmartAlertEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $alert;

    public function __construct($alert)
    {
        $this->alert = $alert;
    }

    public function envelope()
    {
        return new Envelope(
            subject: "[{$this->alert->level}] {$this->alert->title}",
            from: new Address('noreply@stockapp.com', 'StockApp Smart Alerts')
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.smart-alert',
            with: [
                'alert' => $this->alert,
                'levelColor' => $this->getLevelColor(),
                'levelIcon' => $this->getLevelIcon(),
            ]
        );
    }

    public function attachments()
    {
        return [];
    }

    private function getLevelColor()
    {
        return match($this->alert->level) {
            'critical' => '#dc2626',
            'warning' => '#d97706',
            'info' => '#2563eb',
            default => '#6b7280',
        };
    }

    private function getLevelIcon()
    {
        return match($this->alert->level) {
            'critical' => '🚨',
            'warning' => '⚠️',
            'info' => 'ℹ️',
            default => '📢',
        };
    }
}

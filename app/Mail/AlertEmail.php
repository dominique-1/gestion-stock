<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $alert;
    public $product;

    public function __construct($alert, $product = null)
    {
        $this->alert = $alert;
        $this->product = $product;
    }

    public function build()
    {
        return $this->subject('Alerte Stock - ' . $this->alert->message)
                    ->view('emails.alert')
                    ->with([
                        'alert' => $this->alert,
                        'product' => $this->product,
                    ]);
    }
}

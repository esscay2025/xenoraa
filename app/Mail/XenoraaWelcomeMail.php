<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class XenoraaWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $plainPassword;
    public $paymentDetails;

    public function __construct($user, $plainPassword = null, $paymentDetails = null)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
        $this->paymentDetails = $paymentDetails;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Xenoraa — Your Account is Ready! 🚀',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.xenoraa-welcome',
        );
    }
}

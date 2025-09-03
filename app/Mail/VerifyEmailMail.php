<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class VerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function build()
    {
        $htmlMessage = "
            <h2>Email Verification</h2>
            <p>Click the button below to verify your email:</p>
            <a href='{$this->url}'
               style='display:inline-block;background:#007bff;color:#fff;padding:10px 20px;
                      text-decoration:none;border-radius:5px;'>
                Verify Email
            </a>
            <p>If you didn’t create an account, you can ignore this email.</p>
        ";

        return $this->subject('Verify Your Email')
                    ->html($htmlMessage);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Email Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.verify-email',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

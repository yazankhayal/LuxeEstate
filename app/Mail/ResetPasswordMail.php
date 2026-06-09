<?php

namespace App\Mail;

use App\Services\SettingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly string $token,
        public readonly string $email
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $settings = app(SettingService::class)->all();
        $siteName = $settings['site_name'] ?? 'Luxe Estate';

        return new Envelope(
            subject: "Reset Your Password — {$siteName}"
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // 1. Generate the signed password reset URL link safely
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $this->email,
        ]);

        // 2. Fetch runtime settings array
        $settings = app(SettingService::class)->all();

        return new Content(
            view: 'emails.password-reset',
            with: [
                'url' => $url,
                'settings' => $settings,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
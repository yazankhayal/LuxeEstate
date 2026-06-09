<?php

namespace App\Mail;

use App\Models\User;
use App\Services\SettingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginAlertMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly User $user,
        public readonly string $ipAddress,
        public readonly string $userAgent
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $settings = app(SettingService::class)->all();
        $siteName = $settings['site_name'] ?? 'Luxe Estate';

        return new Envelope(
            subject: "Security Alert: New Login Detected — {$siteName}"
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $settings = app(SettingService::class)->all();

        return new Content(
            view: 'emails.login-alert',
            with: [
                'user' => $this->user,
                'ipAddress' => $this->ipAddress,
                'userAgent' => $this->userAgent,
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
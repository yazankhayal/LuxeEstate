<?php

namespace App\Mail;

use App\Models\Contact;
use App\Services\SettingService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Contact $contact)
    {
    }

    public function envelope(): Envelope
    {
        $subject = $this->contact->property_id
            ? "New Property Inquiry – {$this->contact->subject}"
            : "New Contact Inquiry – {$this->contact->name}";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        $settings = app(SettingService::class)->all();

        return new Content(
            view: 'emails.contact-inquiry',
            with: [
                'contact'  => $this->contact, // <-- Ensures $contact variable is globally unbound from context errors
                'settings' => $settings,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
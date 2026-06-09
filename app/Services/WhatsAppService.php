<?php

namespace App\Services;

use App\Models\Contact;
use Twilio\Rest\Client;

class WhatsAppService
{
    protected Client $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function sendContactInquiryNotification(Contact $contact): void
    {
        $to = config('services.twilio.whatsapp_to'); // admin's WhatsApp number
        $from = config('services.twilio.whatsapp_from');

        $this->twilio->messages->create("whatsapp:{$to}", [
            'from' => "whatsapp:{$from}",
            'body' => $this->buildMessage($contact),
        ]);
    }

    protected function buildMessage(Contact $contact): string
    {
        return implode("\n", [
            "📩 New Contact Inquiry",
            "Name: {$contact->name}",
            "Email: {$contact->email}",
            "Phone: {$contact->phone}",
            "Subject: {$contact->subject}",
            "Message: {$contact->message}",
        ]);
    }
}
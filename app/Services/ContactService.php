<?php

namespace App\Services;

use App\DTOs\Contact\ContactInquiryDTO;
use App\Mail\ContactInquiryMail;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;

class ContactService
{
    public function submit(ContactInquiryDTO $dto): Contact
    {
        $contact = Contact::create([
            'name'        => $dto->name,
            'email'       => $dto->email,
            'phone'       => $dto->phone,
            'subject'     => $dto->subject,
            'message'     => $dto->message,
            'property_id' => $dto->propertyId,
        ]);

        // Refresh to ensure enum casts are applied from the database value
        $contact->refresh();

        // Notify admin
        $adminEmail = \App\Models\Setting::get('contact_email');
        if ($adminEmail) {
            Mail::to($adminEmail)->queue(new ContactInquiryMail($contact));
        }

        // Notify admin via WhatsApp
        $adminWhatsApp = \App\Models\Setting::get('contact_whatsapp');
        if ($adminWhatsApp) {
            app(WhatsAppService::class)->sendContactInquiryNotification($contact);
        }

        return $contact;
    }

    public function paginate(array $filters = [], int $perPage = 20)
    {
        return Contact::with('property.translation')
            ->when($filters['status'] ?? null, fn($q, $s) => $q->where('status', $s))
            ->when($filters['search'] ?? null, fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
            )
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function markAsRead(int $id): Contact
    {
        $contact = Contact::findOrFail($id);
        $contact->markAsRead();
        return $contact;
    }

    public function markAsReplied(int $id, ?string $notes = null): Contact
    {
        $contact = Contact::findOrFail($id);
        if ($notes) {
            $contact->update(['admin_notes' => $notes]);
        }
        $contact->markAsReplied();
        return $contact;
    }

    public function delete(int $id): void
    {
        Contact::findOrFail($id)->delete();
    }

    public function getStats(): array
    {
        return [
            'total'    => Contact::count(),
            'new'      => Contact::new()->count(),
            'today'    => Contact::whereDate('created_at', today())->count(),
            'replied'  => Contact::where('status', \App\Enums\ContactStatus::Replied)->count(),
        ];
    }
}

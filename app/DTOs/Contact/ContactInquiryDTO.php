<?php

namespace App\DTOs\Contact;

final class ContactInquiryDTO
{
    public function __construct(
        public readonly string  $name,
        public readonly string  $email,
        public readonly string  $phone,
        public readonly string  $message,
        public readonly ?int    $propertyId,
        public readonly ?string $subject,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name:       $data['name'],
            email:      $data['email'],
            phone:      $data['phone'] ?? '',
            message:    $data['message'],
            propertyId: isset($data['property_id']) ? (int) $data['property_id'] : null,
            subject:    $data['subject'] ?? null,
        );
    }
}

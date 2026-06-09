<?php

namespace Tests\Unit\DTOs;

use App\DTOs\Contact\ContactInquiryDTO;
use PHPUnit\Framework\TestCase;

class ContactInquiryDTOTest extends TestCase
{
    /** @test */
    public function from_request_creates_dto_from_array(): void
    {
        $dto = ContactInquiryDTO::fromRequest([
            'name'        => 'John Doe',
            'email'       => 'john@example.com',
            'phone'       => '+1 555 0000',
            'message'     => 'I am interested in property #5',
            'property_id' => '5',
            'subject'     => 'Property Inquiry',
        ]);

        $this->assertSame('John Doe',                       $dto->name);
        $this->assertSame('john@example.com',               $dto->email);
        $this->assertSame('+1 555 0000',                    $dto->phone);
        $this->assertSame('I am interested in property #5', $dto->message);
        $this->assertSame(5,                                $dto->propertyId);
        $this->assertSame('Property Inquiry',               $dto->subject);
    }

    /** @test */
    public function property_id_is_cast_to_integer(): void
    {
        $dto = ContactInquiryDTO::fromRequest([
            'name' => 'Jane', 'email' => 'jane@test.com',
            'message' => 'Hi', 'property_id' => '12',
        ]);

        $this->assertIsInt($dto->propertyId);
        $this->assertSame(12, $dto->propertyId);
    }

    /** @test */
    public function property_id_defaults_to_null_when_absent(): void
    {
        $dto = ContactInquiryDTO::fromRequest([
            'name' => 'Jane', 'email' => 'jane@test.com', 'message' => 'Hi',
        ]);

        $this->assertNull($dto->propertyId);
    }

    /** @test */
    public function phone_defaults_to_empty_string_when_absent(): void
    {
        $dto = ContactInquiryDTO::fromRequest([
            'name' => 'Jane', 'email' => 'jane@test.com', 'message' => 'Hi',
        ]);

        $this->assertSame('', $dto->phone);
    }

    /** @test */
    public function subject_defaults_to_null_when_absent(): void
    {
        $dto = ContactInquiryDTO::fromRequest([
            'name' => 'Jane', 'email' => 'jane@test.com', 'message' => 'Hi',
        ]);

        $this->assertNull($dto->subject);
    }

    /** @test */
    public function dto_properties_are_readonly(): void
    {
        $dto = ContactInquiryDTO::fromRequest([
            'name' => 'Jane', 'email' => 'jane@test.com', 'message' => 'Hi',
        ]);

        $this->expectException(\Error::class);
        // @phpstan-ignore-next-line
        $dto->name = 'Changed';
    }
}

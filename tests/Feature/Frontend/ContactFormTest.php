<?php

namespace Tests\Feature\Frontend;

use App\Enums\ContactStatus;
use App\Models\Contact;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\Helpers\CreatesUsers;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        $this->seedLanguages();
    }

    /** @test */
    public function contact_page_is_accessible_to_guests(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
        $response->assertInertia(fn ($p) => $p->component('Public/Contact'));
    }

    /** @test */
    public function guest_can_submit_contact_form(): void
    {
        $response = $this->post('/contact', [
            'name'    => 'Ahmed Al-Rashid',
            'email'   => 'ahmed@example.com',
            'phone'   => '+971 50 000 0000',
            'subject' => 'Property Inquiry',
            'message' => 'I would like to learn more about your properties in Istanbul.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contacts', [
            'name'    => 'Ahmed Al-Rashid',
            'email'   => 'ahmed@example.com',
            'status'  => ContactStatus::New->value,
        ]);
    }

    /** @test */
    public function contact_form_requires_name(): void
    {
        $response = $this->post('/contact', [
            'email'   => 'test@example.com',
            'message' => 'Hello there.',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function contact_form_requires_valid_email(): void
    {
        $response = $this->post('/contact', [
            'name'    => 'Test User',
            'email'   => 'not-an-email',
            'message' => 'Hello.',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function contact_form_requires_message(): void
    {
        $response = $this->post('/contact', [
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('message');
    }

    /** @test */
    public function message_must_be_at_least_10_characters(): void
    {
        $response = $this->post('/contact', [
            'name'    => 'Test User',
            'email'   => 'test@example.com',
            'message' => 'Short',
        ]);

        $response->assertSessionHasErrors('message');
    }

    /** @test */
    public function contact_can_be_linked_to_a_property(): void
    {
        $property = Property::factory()->create(['status' => 'active']);

        $this->post('/contact', [
            'name'        => 'Test User',
            'email'       => 'test@example.com',
            'message'     => 'I am interested in this property.',
            'property_id' => $property->id,
        ]);

        $this->assertDatabaseHas('contacts', [
            'email'       => 'test@example.com',
            'property_id' => $property->id,
        ]);
    }

    /** @test */
    public function contact_form_is_rate_limited(): void
    {
        $payload = [
            'name'    => 'Test User',
            'email'   => 'test@example.com',
            'message' => 'Testing rate limit here.',
        ];

        // Submit 5 times (the allowed limit)
        for ($i = 0; $i < 5; $i++) {
            $this->post('/contact', $payload);
        }

        // 6th request should be rate-limited
        $response = $this->post('/contact', $payload);

        $response->assertStatus(429);
    }
}

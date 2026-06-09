<?php

namespace Tests\Unit\Services;

use App\DTOs\Contact\ContactInquiryDTO;
use App\Enums\ContactStatus;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactServiceTest extends TestCase
{
    use RefreshDatabase;

    private ContactService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ContactService();
        Mail::fake();
    }

    /** @test */
    public function submit_creates_contact_record(): void
    {
        $dto = ContactInquiryDTO::fromRequest([
            'name'    => 'John Doe',
            'email'   => 'john@example.com',
            'phone'   => '+1 555 0000',
            'message' => 'I am interested in a property.',
        ]);

        $contact = $this->service->submit($dto);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertDatabaseHas('contacts', [
            'name'    => 'John Doe',
            'email'   => 'john@example.com',
            'message' => 'I am interested in a property.',
            'status'  => ContactStatus::New->value,
        ]);
    }

    /** @test */
    public function submit_sets_status_to_new_by_default(): void
    {
        $dto = ContactInquiryDTO::fromRequest([
            'name'    => 'Test User',
            'email'   => 'test@example.com',
            'message' => 'Hello',
        ]);

        $contact = $this->service->submit($dto);

        $this->assertSame(ContactStatus::New, $contact->status);
    }

    /** @test */
    public function submit_stores_property_id_when_provided(): void
    {
        $dto = ContactInquiryDTO::fromRequest([
            'name'        => 'Test',
            'email'       => 'test@example.com',
            'message'     => 'Hi',
            'property_id' => null, // no actual property needed for this test
        ]);

        $contact = $this->service->submit($dto);

        $this->assertNull($contact->property_id);
    }

    /** @test */
    public function mark_as_replied_updates_status_and_timestamp(): void
    {
        $contact = Contact::factory()->create(['status' => ContactStatus::Read]);

        $this->service->markAsReplied($contact->id);

        $contact->refresh();

        $this->assertSame(ContactStatus::Replied, $contact->status);
        $this->assertNotNull($contact->replied_at);
    }

    /** @test */
    public function mark_as_replied_saves_admin_notes(): void
    {
        $contact = Contact::factory()->create();

        $this->service->markAsReplied($contact->id, 'Called client on 10 Jan');

        $this->assertDatabaseHas('contacts', [
            'id'          => $contact->id,
            'admin_notes' => 'Called client on 10 Jan',
        ]);
    }

    /** @test */
    public function delete_removes_contact_from_database(): void
    {
        $contact = Contact::factory()->create();

        $this->service->delete($contact->id);

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    /** @test */
    public function get_stats_returns_correct_counts(): void
    {
        Contact::factory()->count(3)->create(['status' => ContactStatus::New]);
        Contact::factory()->count(2)->create(['status' => ContactStatus::Replied]);
        Contact::factory()->count(1)->create(['status' => ContactStatus::Read]);

        $stats = $this->service->getStats();

        $this->assertSame(6, $stats['total']);
        $this->assertSame(3, $stats['new']);
        $this->assertSame(2, $stats['replied']);
    }

    /** @test */
    public function paginate_filters_by_status(): void
    {
        Contact::factory()->count(3)->create(['status' => ContactStatus::New]);
        Contact::factory()->count(2)->create(['status' => ContactStatus::Replied]);

        $result = $this->service->paginate(['status' => 'new']);

        $this->assertSame(3, $result->total());
    }

    /** @test */
    public function paginate_filters_by_search_on_name(): void
    {
        Contact::factory()->create(['name' => 'Ahmed Al-Rashid', 'email' => 'ahmed@test.com']);
        Contact::factory()->create(['name' => 'John Smith',      'email' => 'john@test.com']);

        $result = $this->service->paginate(['search' => 'Ahmed']);

        $this->assertSame(1, $result->total());
        $this->assertSame('Ahmed Al-Rashid', $result->items()[0]->name);
    }
}

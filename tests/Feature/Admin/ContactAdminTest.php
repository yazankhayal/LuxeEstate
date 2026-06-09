<?php

namespace Tests\Feature\Admin;

use App\Enums\ContactStatus;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\CreatesUsers;
use Tests\TestCase;

class ContactAdminTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    /** @test */
    public function admin_can_view_contacts_list(): void
    {
        $admin = $this->createAdmin();
        Contact::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get('/admin/contacts');

        $response->assertStatus(200);
        $response->assertInertia(fn ($p) => $p
            ->component('Admin/Contacts/Index')
            ->has('contacts')
            ->has('stats')
        );
    }

    /** @test */
    public function admin_can_view_single_contact(): void
    {
        $admin   = $this->createAdmin();
        $contact = Contact::factory()->create();

        $response = $this->actingAs($admin)->get("/admin/contacts/{$contact->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($p) => $p
            ->component('Admin/Contacts/Show')
            ->has('contact')
        );
    }

    /** @test */
    public function viewing_a_contact_marks_it_as_read(): void
    {
        $admin   = $this->createAdmin();
        $contact = Contact::factory()->create(['status' => ContactStatus::New]);

        $this->actingAs($admin)->get("/admin/contacts/{$contact->id}");

        $this->assertDatabaseHas('contacts', [
            'id'     => $contact->id,
            'status' => ContactStatus::Read->value,
        ]);
    }

    /** @test */
    public function admin_can_mark_contact_as_replied(): void
    {
        $admin   = $this->createAdmin();
        $contact = Contact::factory()->create(['status' => ContactStatus::New]);

        $response = $this->actingAs($admin)->post("/admin/contacts/{$contact->id}/replied", [
            'notes' => 'Called back the client.',
        ]);

        $response->assertRedirect();

        $contact->refresh();
        $this->assertSame(ContactStatus::Replied, $contact->status);
        $this->assertNotNull($contact->replied_at);
        $this->assertSame('Called back the client.', $contact->admin_notes);
    }

    /** @test */
    public function admin_can_delete_a_contact(): void
    {
        $admin   = $this->createAdmin();
        $contact = Contact::factory()->create();

        $response = $this->actingAs($admin)->delete("/admin/contacts/{$contact->id}");

        $response->assertRedirect('/admin/contacts');
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    /** @test */
    public function contacts_can_be_filtered_by_status(): void
    {
        $admin = $this->createAdmin();
        Contact::factory()->count(2)->create(['status' => ContactStatus::New]);
        Contact::factory()->count(3)->create(['status' => ContactStatus::Replied]);

        $response = $this->actingAs($admin)->get('/admin/contacts?status=new');

        $response->assertStatus(200);
        $response->assertInertia(fn ($p) => $p
            ->where('contacts.total', 2)
        );
    }

    /** @test */
    public function guest_cannot_access_contacts(): void
    {
        $this->get('/admin/contacts')->assertRedirect('/login');
    }
}

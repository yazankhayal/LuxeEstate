<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\CreatesUsers;
use Tests\TestCase;

class UserAdminTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    // ─── Access: only super admin can manage users ─────────────────────────────

    /** @test */
    public function admin_can_view_users_list(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertInertia(fn ($p) => $p->component('Admin/Users/Index'));
    }

    /** @test */
    public function editor_cannot_view_users_list(): void
    {
        $editor = $this->createEditor();

        $response = $this->actingAs($editor)->get('/admin/users');

        $response->assertStatus(403);
    }

    /** @test */
    public function viewer_cannot_view_users_list(): void
    {
        $viewer = $this->createViewer();

        $response = $this->actingAs($viewer)->get('/admin/users');

        $response->assertStatus(403);
    }

    // ─── Create ───────────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_create_a_new_user(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name'                  => 'New Editor',
            'email'                 => 'editor@agency.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'editor',
        ]);

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'email' => 'editor@agency.com',
            'role'  => 'editor',
        ]);
    }

    /** @test */
    public function cannot_create_user_with_duplicate_email(): void
    {
        $admin = $this->createAdmin();
        $this->createEditor(['email' => 'taken@example.com']);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name'                  => 'Another',
            'email'                 => 'taken@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'viewer',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function password_confirmation_must_match(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name'                  => 'Test',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'different',
            'role'                  => 'editor',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function role_must_be_valid(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name'                  => 'Test',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'superuser', // invalid
        ]);

        $response->assertSessionHasErrors('role');
    }

    // ─── Edit & Update ────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_view_edit_user_form(): void
    {
        $admin  = $this->createAdmin();
        $editor = $this->createEditor(['email' => 'editor2@test.com']);

        $response = $this->actingAs($admin)->get("/admin/users/{$editor->id}/edit");

        $response->assertStatus(200);
        $response->assertInertia(fn ($p) => $p
            ->component('Admin/Users/Form')
            ->has('user')
        );
    }

    /** @test */
    public function admin_can_update_user_details(): void
    {
        $admin  = $this->createAdmin();
        $editor = $this->createEditor(['email' => 'editor3@test.com', 'name' => 'Old Name']);

        $response = $this->actingAs($admin)->put("/admin/users/{$editor->id}", [
            'name'  => 'New Name',
            'email' => 'editor3@test.com',
            'role'  => 'viewer',
        ]);

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'id'   => $editor->id,
            'name' => 'New Name',
            'role' => 'viewer',
        ]);
    }

    /** @test */
    public function admin_can_change_user_role(): void
    {
        $admin  = $this->createAdmin();
        $editor = $this->createEditor(['email' => 'ed4@test.com']);

        $this->actingAs($admin)->patch("/admin/users/{$editor->id}/role", [
            'role' => 'viewer',
        ]);

        $this->assertDatabaseHas('users', ['id' => $editor->id, 'role' => 'viewer']);
    }

    // ─── Delete ───────────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_delete_another_user(): void
    {
        $admin  = $this->createAdmin();
        $editor = $this->createEditor(['email' => 'del@test.com']);

        $response = $this->actingAs($admin)->delete("/admin/users/{$editor->id}");

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseMissing('users', ['id' => $editor->id]);
    }

    /** @test */
    public function admin_cannot_delete_own_account(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->delete("/admin/users/{$admin->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    /** @test */
    public function editor_cannot_delete_users(): void
    {
        $admin  = $this->createAdmin();
        $editor = $this->createEditor(['email' => 'ed5@test.com']);

        $response = $this->actingAs($editor)->delete("/admin/users/{$admin->id}");

        $response->assertStatus(403);
    }
}

<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\CreatesUsers;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    /** @test */
    public function login_page_is_accessible_to_guests(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_is_redirected_away_from_login(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/login');

        $response->assertRedirect();
    }

    /** @test */
    public function admin_can_log_in_with_valid_credentials(): void
    {
        $admin = $this->createAdmin(['email' => 'admin@test.com']);

        $response = $this->post('/login', [
            'email'    => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
    }

    /** @test */
    public function login_fails_with_wrong_password(): void
    {
        $this->createAdmin(['email' => 'admin@test.com']);

        $response = $this->post('/login', [
            'email'    => 'admin@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function login_fails_with_nonexistent_email(): void
    {
        $response = $this->post('/login', [
            'email'    => 'nobody@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function email_field_is_required(): void
    {
        $response = $this->post('/login', ['password' => 'password']);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function password_field_is_required(): void
    {
        $response = $this->post('/login', ['email' => 'admin@test.com']);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function authenticated_user_can_logout(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)->post('/logout');

        $this->assertGuest();
    }

    /** @test */
    public function logout_redirects_to_home(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post('/logout');

        $response->assertRedirect('/');
    }
}

<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\CreatesUsers;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    // ─── Access Control ───────────────────────────────────────────────────────

    /** @test */
    public function guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function admin_can_access_dashboard(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
    }

    /** @test */
    public function editor_can_access_dashboard(): void
    {
        $editor = $this->createEditor();

        $response = $this->actingAs($editor)->get('/admin');

        $response->assertStatus(200);
    }

    /** @test */
    public function viewer_can_access_dashboard(): void
    {
        $viewer = $this->createViewer();

        $response = $this->actingAs($viewer)->get('/admin');

        $response->assertStatus(200);
    }

    /** @test */
    public function dashboard_contains_stats_data(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Dashboard')
            ->has('stats')
            ->has('recentContacts')
        );
    }
}

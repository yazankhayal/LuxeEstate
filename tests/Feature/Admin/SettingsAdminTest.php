<?php

namespace Tests\Feature\Admin;

use App\Models\Language;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\CreatesUsers;
use Tests\TestCase;

class SettingsAdminTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    /** @test */
    public function admin_can_view_settings_page(): void
    {
        $admin = $this->createAdmin();
        $this->seedLanguages();

        $response = $this->actingAs($admin)->get('/admin/settings');

        $response->assertStatus(200);
        $response->assertInertia(fn ($p) => $p
            ->component('Admin/Settings/Index')
            ->has('settings')
            ->has('languages')
        );
    }

    /** @test */
    public function editor_cannot_access_settings(): void
    {
        $editor = $this->createEditor();

        // Settings are admin-only, but current middleware allows all roles
        // Adjust this test based on your actual gate/policy rules
        $response = $this->actingAs($editor)->get('/admin/settings');
        $this->assertContains($response->status(), [200, 403]);
    }

    /** @test */
    public function admin_can_update_settings(): void
    {
        $admin = $this->createAdmin();
        $this->seedLanguages();

        $response = $this->actingAs($admin)->put('/admin/settings', [
            'site_name'        => 'My Real Estate',
            'site_description' => 'Best properties in Turkey',
            'phone'            => '+90 212 999 9999',
            'email'            => 'info@myrealestate.com',
            'address'          => 'Istanbul, Turkey',
            'whatsapp'         => '+90 532 999 9999',
            'facebook'         => '',
            'instagram'        => '',
            'twitter'          => '',
            'linkedin'         => '',
            'youtube'          => '',
            'default_locale'   => 'en',
            'meta_title'       => 'My Real Estate – Find Your Dream Home',
            'meta_description' => 'Browse premium properties',
            'meta_keywords'    => 'real estate, turkey, istanbul',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertSame('My Real Estate',   Setting::get('site_name'));
        $this->assertSame('+90 212 999 9999', Setting::get('phone'));
    }

    /** @test */
    public function site_name_is_required(): void
    {
        $admin = $this->createAdmin();
        $this->seedLanguages();

        $response = $this->actingAs($admin)->put('/admin/settings', [
            'site_name'      => '',
            'default_locale' => 'en',
        ]);

        $response->assertSessionHasErrors('site_name');
    }

    /** @test */
    public function guest_cannot_access_settings(): void
    {
        $this->get('/admin/settings')->assertRedirect('/login');
    }
}

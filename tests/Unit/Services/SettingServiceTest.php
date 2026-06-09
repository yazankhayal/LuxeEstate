<?php

namespace Tests\Unit\Services;

use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SettingServiceTest extends TestCase
{
    use RefreshDatabase;

    private SettingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SettingService();
    }

    /** @test */
    public function all_returns_all_settings_as_key_value_array(): void
    {
        Setting::create(['key' => 'site_name', 'value' => 'LuxeEstate', 'group' => 'general', 'type' => 'string']);
        Setting::create(['key' => 'phone',     'value' => '+1 000 000', 'group' => 'contact', 'type' => 'string']);

        $all = $this->service->all();

        $this->assertIsArray($all);
        $this->assertSame('LuxeEstate', $all['site_name']);
        $this->assertSame('+1 000 000', $all['phone']);
    }

    /** @test */
    public function update_many_stores_multiple_settings(): void
    {
        $this->service->updateMany([
            'site_name'  => 'MyAgency',
            'phone'      => '+90 555 1234',
            'email'      => 'contact@agency.com',
        ]);

        $this->assertDatabaseHas('settings', ['key' => 'site_name', 'value' => 'MyAgency']);
        $this->assertDatabaseHas('settings', ['key' => 'phone',     'value' => '+90 555 1234']);
        $this->assertDatabaseHas('settings', ['key' => 'email',     'value' => 'contact@agency.com']);
    }

    /** @test */
    public function update_many_updates_existing_settings(): void
    {
        Setting::create(['key' => 'site_name', 'value' => 'OldName', 'group' => 'general', 'type' => 'string']);

        $this->service->updateMany(['site_name' => 'NewName']);

        $this->assertSame('NewName', Setting::get('site_name'));
        $this->assertDatabaseCount('settings', 1);
    }

    /** @test */
    public function get_for_frontend_returns_required_keys(): void
    {
        Setting::create(['key' => 'site_name',    'value' => 'LuxeEstate', 'group' => 'general', 'type' => 'string']);
        Setting::create(['key' => 'phone',        'value' => '+1 000',     'group' => 'contact', 'type' => 'string']);
        Setting::create(['key' => 'email',        'value' => 'a@b.com',    'group' => 'contact', 'type' => 'string']);
        Setting::create(['key' => 'default_locale','value' => 'en',        'group' => 'general', 'type' => 'string']);

        $frontend = $this->service->getForFrontend();

        $this->assertIsArray($frontend);
        $this->assertArrayHasKey('site_name',      $frontend);
        $this->assertArrayHasKey('phone',          $frontend);
        $this->assertArrayHasKey('email',          $frontend);
        $this->assertArrayHasKey('default_locale', $frontend);
        $this->assertSame('LuxeEstate', $frontend['site_name']);
    }

    /** @test */
    public function get_for_frontend_uses_defaults_when_settings_missing(): void
    {
        $frontend = $this->service->getForFrontend();

        $this->assertArrayHasKey('site_name', $frontend);
        $this->assertNotNull($frontend['site_name']);
    }
}

<?php

namespace Tests\Unit\Models;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function set_stores_a_value_by_key(): void
    {
        Setting::set('site_name', 'LuxeEstate');

        $this->assertDatabaseHas('settings', [
            'key'   => 'site_name',
            'value' => 'LuxeEstate',
        ]);
    }

    /** @test */
    public function get_retrieves_a_stored_value(): void
    {
        Setting::create(['key' => 'site_name', 'value' => 'LuxeEstate', 'group' => 'general', 'type' => 'string']);

        $this->assertSame('LuxeEstate', Setting::get('site_name'));
    }

    /** @test */
    public function get_returns_default_when_key_does_not_exist(): void
    {
        $result = Setting::get('nonexistent_key', 'default_value');

        $this->assertSame('default_value', $result);
    }

    /** @test */
    public function get_returns_null_when_key_missing_and_no_default(): void
    {
        $this->assertNull(Setting::get('nonexistent_key'));
    }

    /** @test */
    public function set_updates_existing_key(): void
    {
        Setting::create(['key' => 'phone', 'value' => '+1 000', 'group' => 'contact', 'type' => 'string']);
        Setting::set('phone', '+1 999');

        $this->assertSame('+1 999', Setting::get('phone'));
        $this->assertDatabaseCount('settings', 1);
    }

    /** @test */
    public function boolean_type_is_cast_correctly(): void
    {
        Setting::create(['key' => 'feature_enabled', 'value' => '1', 'group' => 'general', 'type' => 'boolean']);

        $this->assertTrue(Setting::get('feature_enabled'));
    }

    /** @test */
    public function integer_type_is_cast_correctly(): void
    {
        Setting::create(['key' => 'max_items', 'value' => '25', 'group' => 'general', 'type' => 'integer']);

        $value = Setting::get('max_items');

        $this->assertSame(25, $value);
        $this->assertIsInt($value);
    }

    /** @test */
    public function json_type_is_decoded_correctly(): void
    {
        Setting::create(['key' => 'social_links', 'value' => '{"facebook":"https://fb.com"}', 'group' => 'social', 'type' => 'json']);

        $value = Setting::get('social_links');

        $this->assertIsArray($value);
        $this->assertSame('https://fb.com', $value['facebook']);
    }

    /** @test */
    public function group_returns_all_settings_in_a_group(): void
    {
        Setting::create(['key' => 'phone',  'value' => '+1 000', 'group' => 'contact', 'type' => 'string']);
        Setting::create(['key' => 'email',  'value' => 'a@b.com','group' => 'contact', 'type' => 'string']);
        Setting::create(['key' => 'logo',   'value' => 'logo.png','group' => 'general', 'type' => 'string']);

        $group = Setting::group('contact');

        $this->assertCount(2, $group);
        $this->assertArrayHasKey('phone', $group);
        $this->assertArrayHasKey('email', $group);
        $this->assertArrayNotHasKey('logo', $group);
    }
}

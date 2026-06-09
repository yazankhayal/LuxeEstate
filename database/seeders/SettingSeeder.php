<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name',        'value' => 'LuxeEstate',                          'group' => 'general', 'type' => 'string'],
            ['key' => 'site_description', 'value' => 'Premium Real Estate Agency',           'group' => 'general', 'type' => 'string'],
            ['key' => 'default_locale',   'value' => 'en',                                   'group' => 'general', 'type' => 'string'],
            ['key' => 'logo',             'value' => null,                                   'group' => 'general', 'type' => 'string'],
            ['key' => 'favicon',          'value' => null,                                   'group' => 'general', 'type' => 'string'],
            // Contact
            ['key' => 'phone',            'value' => '+90 212 000 0000',                     'group' => 'contact', 'type' => 'string'],
            ['key' => 'email',            'value' => 'info@luxeestate.com',                  'group' => 'contact', 'type' => 'string'],
            ['key' => 'address',          'value' => 'Istanbul, Turkey',                     'group' => 'contact', 'type' => 'string'],
            ['key' => 'whatsapp',         'value' => '+90 532 000 0000',                     'group' => 'contact', 'type' => 'string'],
            ['key' => 'contact_email',    'value' => 'admin@luxeestate.com',                 'group' => 'contact', 'type' => 'string'],
            // Social
            ['key' => 'facebook',         'value' => '',                                     'group' => 'social',  'type' => 'string'],
            ['key' => 'instagram',        'value' => '',                                     'group' => 'social',  'type' => 'string'],
            ['key' => 'twitter',          'value' => '',                                     'group' => 'social',  'type' => 'string'],
            ['key' => 'linkedin',         'value' => '',                                     'group' => 'social',  'type' => 'string'],
            ['key' => 'youtube',          'value' => '',                                     'group' => 'social',  'type' => 'string'],
            // SEO
            ['key' => 'meta_title',       'value' => 'LuxeEstate – Premium Properties',     'group' => 'seo',     'type' => 'string'],
            ['key' => 'meta_description', 'value' => 'Find your dream property with LuxeEstate.', 'group' => 'seo', 'type' => 'string'],
            ['key' => 'meta_keywords',    'value' => 'real estate, property, istanbul',      'group' => 'seo',     'type' => 'string'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}

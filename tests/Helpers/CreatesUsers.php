<?php

namespace Tests\Helpers;

use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait CreatesUsers
{
    protected function createAdmin(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'name'     => 'Super Admin',
            'email'    => 'admin@test.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ], $overrides));
    }

    protected function createEditor(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'name'     => 'Editor User',
            'email'    => 'editor@test.com',
            'password' => Hash::make('password'),
            'role'     => 'editor',
        ], $overrides));
    }

    protected function createViewer(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'name'     => 'Viewer User',
            'email'    => 'viewer@test.com',
            'password' => Hash::make('password'),
            'role'     => 'viewer',
        ], $overrides));
    }

    protected function seedLanguages(): void
    {
        $languages = [
            ['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'direction' => 'ltr', 'flag' => '🇬🇧', 'is_default' => true,  'is_active' => true],
            ['code' => 'ar', 'name' => 'Arabic',  'native_name' => 'العربية', 'direction' => 'rtl', 'flag' => '🇸🇦', 'is_default' => false, 'is_active' => true],
            ['code' => 'tr', 'name' => 'Turkish', 'native_name' => 'Türkçe',  'direction' => 'ltr', 'flag' => '🇹🇷', 'is_default' => false, 'is_active' => true],
        ];

        foreach ($languages as $lang) {
            Language::updateOrCreate(['code' => $lang['code']], $lang);
        }
    }
}

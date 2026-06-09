<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            [
                'code'        => 'en',
                'name'        => 'English',
                'native_name' => 'English',
                'direction'   => 'ltr',
                'flag'        => '🇬🇧',
                'is_default'  => true,
                'is_active'   => true,
            ],
            [
                'code'        => 'ar',
                'name'        => 'Arabic',
                'native_name' => 'العربية',
                'direction'   => 'rtl',
                'flag'        => '🇸🇦',
                'is_default'  => false,
                'is_active'   => true,
            ],
            [
                'code'        => 'tr',
                'name'        => 'Turkish',
                'native_name' => 'Türkçe',
                'direction'   => 'ltr',
                'flag'        => '🇹🇷',
                'is_default'  => false,
                'is_active'   => true,
            ],
        ];

        foreach ($languages as $lang) {
            Language::updateOrCreate(['code' => $lang['code']], $lang);
        }
    }
}

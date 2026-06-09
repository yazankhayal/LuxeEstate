<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Service;
use App\Models\ServiceTranslation;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            SettingSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            ServiceSeeder::class,
        ]);
    }
}

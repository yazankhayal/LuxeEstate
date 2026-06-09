<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'slug'        => 'news',
                'name'        => 'News',
                'locale_name' => ['en' => 'News',       'ar' => 'أخبار',       'tr' => 'Haberler'],
            ],
            [
                'slug'        => 'tips',
                'name'        => 'Tips',
                'locale_name' => ['en' => 'Tips',       'ar' => 'نصائح',       'tr' => 'İpuçları'],
            ],
            [
                'slug'        => 'market',
                'name'        => 'Market',
                'locale_name' => ['en' => 'Market',     'ar' => 'السوق',       'tr' => 'Pazar'],
            ],
            [
                'slug'        => 'investment',
                'name'        => 'Investment',
                'locale_name' => ['en' => 'Investment', 'ar' => 'استثمار',     'tr' => 'Yatırım'],
            ],
            [
                'slug'        => 'lifestyle',
                'name'        => 'Lifestyle',
                'locale_name' => ['en' => 'Lifestyle',  'ar' => 'أسلوب حياة', 'tr' => 'Yaşam Tarzı'],
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}

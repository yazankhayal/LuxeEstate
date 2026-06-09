<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceTranslation;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'slug'  => 'buying',
                'icon'  => 'home',
                'order' => 1,
                'translations' => [
                    'en' => ['title' => 'Property Buying',        'description' => 'Expert guidance through every step of your property purchase.'],
                    'ar' => ['title' => 'شراء العقارات',           'description' => 'إرشادات متخصصة في كل خطوة من خطوات شراء عقارك.'],
                    'tr' => ['title' => 'Mülk Satın Alma',         'description' => 'Mülk satın alma sürecinizin her adımında uzman rehberlik.'],
                ],
            ],
            [
                'slug'  => 'renting',
                'icon'  => 'key',
                'order' => 2,
                'translations' => [
                    'en' => ['title' => 'Property Renting',        'description' => 'Find the perfect rental property tailored to your needs.'],
                    'ar' => ['title' => 'تأجير العقارات',           'description' => 'ابحث عن العقار المثالي للإيجار وفقاً لاحتياجاتك.'],
                    'tr' => ['title' => 'Mülk Kiralama',            'description' => 'İhtiyaçlarınıza göre mükemmel kiralık mülkü bulun.'],
                ],
            ],
            [
                'slug'  => 'consulting',
                'icon'  => 'briefcase',
                'order' => 3,
                'translations' => [
                    'en' => ['title' => 'Real Estate Consulting',   'description' => 'Strategic advice for smart real estate decisions.'],
                    'ar' => ['title' => 'استشارات عقارية',           'description' => 'نصائح استراتيجية لاتخاذ قرارات عقارية ذكية.'],
                    'tr' => ['title' => 'Gayrimenkul Danışmanlığı', 'description' => 'Akıllı gayrimenkul kararları için stratejik tavsiye.'],
                ],
            ],
            [
                'slug'  => 'valuation',
                'icon'  => 'chart-bar',
                'order' => 4,
                'translations' => [
                    'en' => ['title' => 'Property Valuation',       'description' => 'Accurate market valuations by certified appraisers.'],
                    'ar' => ['title' => 'تقييم العقارات',            'description' => 'تقييمات سوقية دقيقة من قِبل مثمنين معتمدين.'],
                    'tr' => ['title' => 'Mülk Değerleme',            'description' => 'Sertifikalı eksperler tarafından doğru piyasa değerlemeleri.'],
                ],
            ],
            [
                'slug'  => 'property-management',
                'icon'  => 'cog',
                'order' => 5,
                'translations' => [
                    'en' => ['title' => 'Property Management',      'description' => 'Full-service management for landlords and investors.'],
                    'ar' => ['title' => 'إدارة العقارات',            'description' => 'خدمة إدارة متكاملة للملاك والمستثمرين.'],
                    'tr' => ['title' => 'Mülk Yönetimi',             'description' => 'Ev sahipleri ve yatırımcılar için tam hizmet yönetimi.'],
                ],
            ],
            [
                'slug'  => 'investment-advisory',
                'icon'  => 'trending-up',
                'order' => 6,
                'translations' => [
                    'en' => ['title' => 'Investment Advisory',      'description' => 'Maximize ROI with data-driven investment strategies.'],
                    'ar' => ['title' => 'الاستشارات الاستثمارية',    'description' => 'تعظيم العوائد من خلال استراتيجيات استثمارية مبنية على البيانات.'],
                    'tr' => ['title' => 'Yatırım Danışmanlığı',      'description' => "Veri odaklı yatırım stratejileriyle ROI'yi maksimize edin."],
                ],
            ],
        ];

        foreach ($services as $serviceData) {
            $service = Service::updateOrCreate(
                ['slug' => $serviceData['slug']],
                [
                    'icon'      => $serviceData['icon'],
                    'order'     => $serviceData['order'],
                    'is_active' => true,
                ]
            );

            foreach ($serviceData['translations'] as $locale => $trans) {
                ServiceTranslation::updateOrCreate(
                    ['service_id' => $service->id, 'locale' => $locale],
                    $trans
                );
            }
        }
    }
}

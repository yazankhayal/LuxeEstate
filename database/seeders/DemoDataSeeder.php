<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'your-email@domain.com')->first();

        if (! $admin) {
            $this->command->warn('Admin user not found - run DatabaseSeeder first.');
            return;
        }

        $this->seedProperties();
        $this->seedBlogPosts($admin->id);
    }

    private function seedProperties(): void
    {
        $items = [
            ['slug'=>'luxury-villa-istanbul','type'=>'sale','status'=>'active','price'=>850000,'currency'=>'USD','city'=>'Istanbul','country'=>'Turkey','location'=>'Besiktas','area'=>320,'bedrooms'=>4,'bathrooms'=>3,'is_featured'=>true,
             'en'=>['title'=>'Luxury Villa in Besiktas','description'=>'Stunning 4-bedroom villa with Bosphorus views.','address'=>'Besiktas, Istanbul'],
             'ar'=>['title'=>'فيلا فاخرة في بيشيكتاش','description'=>'فيلا رائعة من 4 غرف نوم مع إطلالة على البوسفور.','address'=>'بيشيكتاش، إسطنبول'],
             'tr'=>['title'=>"Besiktas'ta Lüks Villa",'description'=>'Bogazmanzarali muhtes 4 yatak odali villa.','address'=>'Besiktas, Istanbul']],
            ['slug'=>'modern-apartment-antalya','type'=>'sale','status'=>'active','price'=>175000,'currency'=>'USD','city'=>'Antalya','country'=>'Turkey','location'=>'Lara','area'=>110,'bedrooms'=>2,'bathrooms'=>1,'is_featured'=>true,
             'en'=>['title'=>'Modern Apartment in Lara','description'=>'Brand new 2+1 apartment with sea views.','address'=>'Lara, Antalya'],
             'ar'=>['title'=>'شقة عصرية في لارا','description'=>'شقة جديدة من غرفتين مع إطلالة على البحر.','address'=>'لارا، أنطاليا'],
             'tr'=>['title'=>"Lara'da Modern Daire",'description'=>'Deniz manzarali yeni 2+1 daire.','address'=>'Lara, Antalya']],
            ['slug'=>'beachfront-apt-rent-alanya','type'=>'rent','status'=>'active','price'=>1200,'currency'=>'USD','city'=>'Alanya','country'=>'Turkey','location'=>'Cleopatra Beach','area'=>85,'bedrooms'=>2,'bathrooms'=>1,'is_featured'=>true,
             'en'=>['title'=>'Beachfront Apartment for Rent','description'=>'Beautiful beachfront apartment by Cleopatra Beach.','address'=>'Cleopatra Beach, Alanya'],
             'ar'=>['title'=>'شقة على الشاطئ للإيجار','description'=>'شقة جميلة على شاطئ كليوباترا.','address'=>'شاطئ كليوباترا، ألانيا'],
             'tr'=>['title'=>'Kiralik Sahil Dairesi','description'=>'Kleopatra Plaji yakininda daire.','address'=>'Kleopatra Plaji, Alanya']],
            ['slug'=>'penthouse-izmir-konak','type'=>'sale','status'=>'active','price'=>420000,'currency'=>'USD','city'=>'Izmir','country'=>'Turkey','location'=>'Konak','area'=>210,'bedrooms'=>3,'bathrooms'=>2,'is_featured'=>true,
             'en'=>['title'=>'Penthouse in Konak, Izmir','description'=>'Spectacular penthouse with panoramic bay views.','address'=>'Konak, Izmir'],
             'ar'=>['title'=>'بنتهاوس في كوناك، إزمير','description'=>'بنتهاوس مذهل مع إطلالات بانورامية.','address'=>'كوناك، إزمير'],
             'tr'=>['title'=>'Konak Cati Kati','description'=>'Panoramik manzarali harika cati katı.','address'=>'Konak, Izmir']],
            ['slug'=>'office-rent-istanbul-maslak','type'=>'rent','status'=>'active','price'=>3500,'currency'=>'USD','city'=>'Istanbul','country'=>'Turkey','location'=>'Maslak','area'=>150,'bedrooms'=>null,'bathrooms'=>2,'is_featured'=>false,
             'en'=>['title'=>'Premium Office Space in Maslak','description'=>'Class A office in Istanbul business district.','address'=>'Maslak, Istanbul'],
             'ar'=>['title'=>'مكتب متميز في مسلك','description'=>'مساحة مكتبية من الدرجة الأولى.','address'=>'مسلك، إسطنبول'],
             'tr'=>['title'=>"Maslak'ta Premium Ofis",'description'=>'A sinifi ofis alani.','address'=>'Maslak, Istanbul']],
        ];

        foreach ($items as $d) {
            $p = Property::updateOrCreate(['slug' => $d['slug']], [
                'type'=>$d['type'],'status'=>$d['status'],'price'=>$d['price'],'currency'=>$d['currency'],
                'city'=>$d['city'],'country'=>$d['country'],'location'=>$d['location'],'area'=>$d['area'],
                'bedrooms'=>$d['bedrooms'],'bathrooms'=>$d['bathrooms'],'is_featured'=>$d['is_featured'],
            ]);
            foreach (['en','ar','tr'] as $locale) {
                if (isset($d[$locale])) {
                    $p->translations()->updateOrCreate(['locale'=>$locale], array_merge($d[$locale], ['features'=>['Parking','Security','Air Conditioning']]));
                }
            }
        }
        $this->command->info('Seeded '.count($items).' demo properties.');
    }

    private function seedBlogPosts(int $authorId): void
    {
        $categories = Category::all()->keyBy('slug');
        if ($categories->isEmpty()) { $this->command->warn('No categories found.'); return; }

        $posts = [
            ['cat'=>'market',
             'en'=>['title'=>'Top 5 Neighborhoods to Invest in Istanbul 2025','excerpt'=>'Istanbul real estate attracts global investors.','content'=>'<p>Istanbul remains one of the world\'s most dynamic real estate markets.</p>'],
             'ar'=>['title'=>'أفضل 5 أحياء للاستثمار في إسطنبول 2025','excerpt'=>'سوق العقارات في إسطنبول.','content'=>'<p>تظل إسطنبول أحد أكثر أسواق العقارات ديناميكية.</p>'],
             'tr'=>['title'=>"Istanbul'da Yatirim Yapilacak En Iyi 5 Semt",'excerpt'=>'Istanbul pazari.','content'=>'<p>Istanbul dinamik bir pazar olmaya devam ediyor.</p>']],
            ['cat'=>'tips',
             'en'=>['title'=>"A First-Time Buyer's Guide to Property in Turkey",'excerpt'=>'Navigating the Turkish property market as a foreigner.','content'=>'<p>Buying property in Turkey as a foreign national is straightforward once you understand the process.</p>'],
             'ar'=>['title'=>'دليل المشتري لأول مرة للعقارات في تركيا','excerpt'=>'التنقل في سوق العقارات التركي.','content'=>'<p>شراء العقارات في تركيا أمر بسيط بمجرد أن تفهم العملية.</p>'],
             'tr'=>['title'=>"Turkiye'de Ilk Kez Ev Alacaklar Icin Rehber",'excerpt'=>'Turk gayrimenkul piyasasi rehberi.','content'=>'<p>Yabanci olarak Turkiye\'de mulk satin almak kolaydır.</p>']],
            ['cat'=>'investment',
             'en'=>['title'=>"Turkey Citizenship by Investment Program Explained",'excerpt'=>'Turkey offers citizenship for $400,000 property purchase.','content'=>'<p>The Turkish Citizenship by Investment program has attracted buyers from over 100 countries.</p>'],
             'ar'=>['title'=>'برنامج الجنسية التركية عن طريق الاستثمار','excerpt'=>'الجنسية التركية مقابل شراء عقار بقيمة 400,000 دولار.','content'=>'<p>اجتذب البرنامج مشترين من أكثر من 100 دولة.</p>'],
             'tr'=>['title'=>"Turkiye Yatirim Yoluyla Vatandaslik Programi",'excerpt'=>'400.000 dolar yatirima vatandaslik.','content'=>'<p>Program 100 ulkeden alici cekmistir.</p>']],
        ];

        foreach ($posts as $d) {
            $cat = $categories->get($d['cat']) ?? $categories->first();
            $slug = Str::slug($d['en']['title']);
            $post = Post::updateOrCreate(['slug'=>$slug], [
                'category_id'=>$cat->id,'author_id'=>$authorId,
                'is_published'=>true,'published_at'=>now()->subDays(rand(1,30)),
            ]);
            foreach (['en','ar','tr'] as $locale) {
                if (isset($d[$locale])) {
                    $post->translations()->updateOrCreate(['locale'=>$locale], $d[$locale]);
                }
            }
        }
        $this->command->info('Seeded '.count($posts).' demo blog posts.');
    }
}

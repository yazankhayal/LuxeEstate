<?php

namespace Tests\Unit\Services;

use App\DTOs\Property\CreatePropertyDTO;
use App\DTOs\Property\UpdatePropertyDTO;
use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Models\Property;
use App\Models\PropertyTranslation;
use App\Services\PropertyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyServiceTest extends TestCase
{
    use RefreshDatabase;

    private PropertyService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PropertyService();
    }

    private function makeCreateDTO(array $overrides = []): CreatePropertyDTO
    {
        return CreatePropertyDTO::fromRequest(array_merge([
            'slug'         => 'test-property-'.rand(1, 9999),
            'type'         => 'sale',
            'status'       => 'active',
            'price'        => '250000',
            'currency'     => 'USD',
            'location'     => 'Besiktas',
            'city'         => 'Istanbul',
            'country'      => 'Turkey',
            'area'         => '150',
            'is_featured'  => false,
            'translations' => [
                'en' => ['title' => 'Test Property', 'description' => 'A test property description.'],
            ],
        ], $overrides));
    }

    /** @test */
    public function create_persists_property_to_database(): void
    {
        $dto = $this->makeCreateDTO(['slug' => 'my-test-villa']);

        $property = $this->service->create($dto);

        $this->assertInstanceOf(Property::class, $property);
        $this->assertDatabaseHas('properties', [
            'slug'   => 'my-test-villa',
            'type'   => 'sale',
            'status' => 'active',
        ]);
    }

    /** @test */
    public function create_stores_english_translation(): void
    {
        $dto = $this->makeCreateDTO([
            'translations' => [
                'en' => ['title' => 'Beautiful Villa', 'description' => 'A lovely villa in Istanbul.'],
            ],
        ]);

        $property = $this->service->create($dto);

        $this->assertDatabaseHas('property_translations', [
            'property_id' => $property->id,
            'locale'      => 'en',
            'title'       => 'Beautiful Villa',
        ]);
    }

    /** @test */
    public function create_stores_multiple_translations(): void
    {
        $dto = $this->makeCreateDTO([
            'translations' => [
                'en' => ['title' => 'EN Title', 'description' => 'EN Desc'],
                'ar' => ['title' => 'عنوان عربي', 'description' => 'وصف عربي'],
                'tr' => ['title' => 'TR Başlık',  'description' => 'TR Açıklama'],
            ],
        ]);

        $property = $this->service->create($dto);

        $this->assertSame(3, $property->translations()->count());
        $this->assertDatabaseHas('property_translations', ['property_id' => $property->id, 'locale' => 'ar']);
        $this->assertDatabaseHas('property_translations', ['property_id' => $property->id, 'locale' => 'tr']);
    }

    /** @test */
    public function update_changes_property_fields(): void
    {
        $property = Property::factory()->create(['slug' => 'old-slug', 'price' => 100000]);

        $dto = UpdatePropertyDTO::fromRequest($property->id, [
            'slug'         => 'new-slug',
            'type'         => 'rent',
            'status'       => 'active',
            'price'        => '200000',
            'currency'     => 'USD',
            'location'     => 'Kadikoy',
            'city'         => 'Istanbul',
            'country'      => 'Turkey',
            'area'         => '80',
            'is_featured'  => false,
            'translations' => ['en' => ['title' => 'Updated Title', 'description' => 'Updated.']],
        ]);

        $updated = $this->service->update($dto);

        $this->assertSame('new-slug', $updated->slug);
        $this->assertSame(200000.0,   $updated->price);
        $this->assertSame(PropertyType::Rent, $updated->type);
    }

    /** @test */
    public function update_syncs_translations(): void
    {
        $property = Property::factory()->create();
        $property->translations()->create(['locale' => 'en', 'title' => 'Old Title', 'description' => 'Old']);

        $dto = UpdatePropertyDTO::fromRequest($property->id, [
            'slug'         => $property->slug,
            'type'         => $property->type->value,
            'status'       => $property->status->value,
            'price'        => (string) $property->price,
            'currency'     => $property->currency,
            'location'     => $property->location,
            'city'         => $property->city,
            'country'      => $property->country,
            'area'         => (string) $property->area,
            'is_featured'  => false,
            'translations' => ['en' => ['title' => 'New Title', 'description' => 'New Description']],
        ]);

        $this->service->update($dto);

        $this->assertDatabaseHas('property_translations', [
            'property_id' => $property->id,
            'locale'      => 'en',
            'title'       => 'New Title',
        ]);
    }

    /** @test */
    public function delete_removes_property_from_database(): void
    {
        $property = Property::factory()->create();

        $this->service->delete($property->id);

        $this->assertSoftDeleted('properties', ['id' => $property->id]);
    }

    /** @test */
    public function delete_keeps_translations_when_property_is_soft_deleted(): void
    {
        $property = Property::factory()->create();

        $property->translations()->create([
            'locale' => 'en',
            'title' => 'Test',
            'description' => 'Desc',
        ]);

        $this->service->delete($property->id);

        $this->assertSoftDeleted('properties', [
            'id' => $property->id,
        ]);

        $this->assertDatabaseHas('property_translations', [
            'property_id' => $property->id,
        ]);
    }

    /** @test */
    public function get_featured_returns_only_featured_active_properties(): void
    {
        Property::factory()->count(3)->create(['is_featured' => true,  'status' => 'active']);
        Property::factory()->count(2)->create(['is_featured' => false, 'status' => 'active']);
        Property::factory()->count(1)->create(['is_featured' => true,  'status' => 'inactive']);

        $featured = $this->service->getFeatured(10);

        $this->assertCount(3, $featured);
        $featured->each(fn($p) => $this->assertTrue($p->is_featured));
    }

    /** @test */
    public function get_featured_respects_limit(): void
    {
        Property::factory()->count(10)->create(['is_featured' => true, 'status' => 'active']);

        $featured = $this->service->getFeatured(4);

        $this->assertCount(4, $featured);
    }

    /** @test */
    public function paginate_filters_by_type(): void
    {
        Property::factory()->count(4)->create(['type' => 'sale', 'status' => 'active']);
        Property::factory()->count(2)->create(['type' => 'rent', 'status' => 'active']);

        $result = $this->service->paginate(['type' => 'sale'], 20);

        $this->assertSame(4, $result->total());
    }

    /** @test */
    public function paginate_filters_by_city(): void
    {
        Property::factory()->count(3)->create(['city' => 'Istanbul', 'status' => 'active']);
        Property::factory()->count(2)->create(['city' => 'Ankara',   'status' => 'active']);

        $result = $this->service->paginate(['city' => 'Istanbul'], 20);

        $this->assertSame(3, $result->total());
    }

    /** @test */
    public function paginate_filters_by_price_range(): void
    {
        Property::factory()->create(['price' => 100000, 'status' => 'active']);
        Property::factory()->create(['price' => 250000, 'status' => 'active']);
        Property::factory()->create(['price' => 500000, 'status' => 'active']);

        $result = $this->service->paginate([
            'min_price' => 150000,
            'max_price' => 400000,
        ], 20);

        $this->assertSame(1, $result->total());
    }

    /** @test */
    public function paginate_only_returns_active_properties(): void
    {
        Property::factory()->count(3)->create(['status' => 'active']);
        Property::factory()->count(2)->create(['status' => 'inactive']);
        Property::factory()->count(1)->create(['status' => 'sold']);

        $result = $this->service->paginate([], 20);

        $this->assertSame(3, $result->total());
    }
}

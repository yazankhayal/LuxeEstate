<?php

namespace Tests\Unit\DTOs;

use App\DTOs\Property\CreatePropertyDTO;
use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use PHPUnit\Framework\TestCase;

class CreatePropertyDTOTest extends TestCase
{
    private function validData(array $overrides = []): array
    {
        return array_merge([
            'slug'             => 'luxury-villa-istanbul',
            'type'             => 'sale',
            'status'           => 'active',
            'price'            => '850000',
            'currency'         => 'USD',
            'location'         => 'Besiktas',
            'city'             => 'Istanbul',
            'country'          => 'Turkey',
            'area'             => '320',
            'bedrooms'         => '4',
            'bathrooms'        => '3',
            'is_featured'      => true,
            'whatsapp'         => '+90 532 000 0000',
            'phone'            => '+90 212 000 0000',
            'video_url'        => null,
            'meta_title'       => 'Luxury Villa SEO Title',
            'meta_description' => 'SEO description here',
            'meta_keywords'    => 'villa, istanbul, luxury',
            'translations'     => [
                'en' => ['title' => 'Luxury Villa', 'description' => 'A beautiful villa'],
                'ar' => ['title' => 'فيلا فاخرة',   'description' => 'فيلا جميلة'],
            ],
        ], $overrides);
    }

    /** @test */
    public function from_request_creates_dto_with_correct_types(): void
    {
        $dto = CreatePropertyDTO::fromRequest($this->validData());

        $this->assertInstanceOf(PropertyType::class,   $dto->type);
        $this->assertInstanceOf(PropertyStatus::class, $dto->status);
        $this->assertSame(PropertyType::Sale,          $dto->type);
        $this->assertSame(PropertyStatus::Active,      $dto->status);
    }

    /** @test */
    public function price_and_area_are_cast_to_float(): void
    {
        $dto = CreatePropertyDTO::fromRequest($this->validData());

        $this->assertIsFloat($dto->price);
        $this->assertIsFloat($dto->area);
        $this->assertSame(850000.0, $dto->price);
        $this->assertSame(320.0,    $dto->area);
    }

    /** @test */
    public function bedrooms_and_bathrooms_are_cast_to_int(): void
    {
        $dto = CreatePropertyDTO::fromRequest($this->validData());

        $this->assertIsInt($dto->bedrooms);
        $this->assertIsInt($dto->bathrooms);
        $this->assertSame(4, $dto->bedrooms);
        $this->assertSame(3, $dto->bathrooms);
    }

    /** @test */
    public function nullable_bedrooms_returns_null(): void
    {
        $dto = CreatePropertyDTO::fromRequest($this->validData(['bedrooms' => null]));

        $this->assertNull($dto->bedrooms);
    }

    /** @test */
    public function is_featured_is_cast_to_bool(): void
    {
        $dtoTrue  = CreatePropertyDTO::fromRequest($this->validData(['is_featured' => '1']));
        $dtoFalse = CreatePropertyDTO::fromRequest($this->validData(['is_featured' => false]));

        $this->assertTrue($dtoTrue->isFeatured);
        $this->assertFalse($dtoFalse->isFeatured);
    }

    /** @test */
    public function translations_are_preserved(): void
    {
        $dto = CreatePropertyDTO::fromRequest($this->validData());

        $this->assertArrayHasKey('en', $dto->translations);
        $this->assertArrayHasKey('ar', $dto->translations);
        $this->assertSame('Luxury Villa', $dto->translations['en']['title']);
        $this->assertSame('فيلا فاخرة',   $dto->translations['ar']['title']);
    }

    /** @test */
    public function optional_fields_default_to_null(): void
    {
        $dto = CreatePropertyDTO::fromRequest($this->validData([
            'video_url'  => null,
            'latitude'   => null,
            'longitude'  => null,
        ]));

        $this->assertNull($dto->videoUrl);
        $this->assertNull($dto->latitude);
        $this->assertNull($dto->longitude);
    }

    /** @test */
    public function dto_is_immutable_readonly(): void
    {
        $dto = CreatePropertyDTO::fromRequest($this->validData());

        $this->expectException(\Error::class);
        // @phpstan-ignore-next-line
        $dto->slug = 'changed';
    }

    /** @test */
    public function rent_type_is_correctly_mapped(): void
    {
        $dto = CreatePropertyDTO::fromRequest($this->validData(['type' => 'rent']));

        $this->assertSame(PropertyType::Rent, $dto->type);
    }
}

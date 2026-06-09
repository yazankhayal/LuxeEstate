<?php

namespace Database\Factories;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(PropertyType::cases());
        $slug = Str::slug($this->faker->words(3, true)) . '-' . $this->faker->unique()->numberBetween(1, 99999);

        return [
            'slug'         => $slug,
            'type'         => $type,
            'status'       => PropertyStatus::Active,
            'price'        => $this->faker->randomFloat(0, 50000, 2000000),
            'currency'     => $this->faker->randomElement(['USD', 'EUR', 'TRY']),
            'location'     => $this->faker->streetName(),
            'city'         => $this->faker->randomElement(['Istanbul', 'Ankara', 'Izmir', 'Antalya', 'Bursa']),
            'country'      => 'Turkey',
            'area'         => $this->faker->randomFloat(0, 50, 500),
            'bedrooms'     => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'bathrooms'    => $this->faker->randomElement([1, 2, 3]),
            'is_featured'  => false,
            'latitude'     => $this->faker->latitude(36, 42),
            'longitude'    => $this->faker->longitude(26, 45),
            'views_count'  => 0,
        ];
    }

    // NOTE: configure() intentionally omitted.
    // Translations must be added explicitly in tests or seeders to avoid
    // UniqueConstraintViolation when tests create their own translations.

    public function withTranslation(string $title = null, string $locale = 'en'): static
    {
        return $this->afterCreating(function (Property $property) use ($title, $locale) {
            $property->translations()->firstOrCreate(
                ['locale' => $locale],
                [
                    'title'       => $title ?? $this->faker->words(4, true) . ' Property',
                    'description' => $this->faker->paragraph(),
                    'address'     => $this->faker->streetAddress(),
                    'features'    => ['Parking', 'Security'],
                ]
            );
        });
    }

    public function active(): static
    {
        return $this->state(['status' => PropertyStatus::Active]);
    }

    public function inactive(): static
    {
        return $this->state(['status' => PropertyStatus::Inactive]);
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }

    public function forSale(): static
    {
        return $this->state(['type' => PropertyType::Sale]);
    }

    public function forRent(): static
    {
        return $this->state(['type' => PropertyType::Rent]);
    }
}

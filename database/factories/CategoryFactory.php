<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'slug'        => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'name'        => ucfirst($name),
            'locale_name' => [
                'en' => ucfirst($name),
                'ar' => ucfirst($name),
                'tr' => ucfirst($name),
            ],
        ];
    }
}

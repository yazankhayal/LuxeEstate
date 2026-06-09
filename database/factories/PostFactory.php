<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(6);

        return [
            'slug'         => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1, 99999),
            'category_id'  => Category::factory(),
            'author_id'    => User::factory(),
            'is_published' => true,
            'published_at' => now()->subDays(rand(1, 30)),
            'views_count'  => 0,
        ];
    }

    // NOTE: configure() omitted intentionally — no auto-translations.
    // Tests and seeders must add translations explicitly using withTranslation()
    // or $post->translations()->create() to avoid UniqueConstraintViolation.

    public function withTranslation(string $title = null, string $locale = 'en'): static
    {
        return $this->afterCreating(function (Post $post) use ($title, $locale) {
            $post->translations()->firstOrCreate(
                ['locale' => $locale],
                [
                    'title'   => $title ?? $this->faker->sentence(6),
                    'excerpt' => $this->faker->sentence(12),
                    'content' => '<p>' . $this->faker->paragraph() . '</p>',
                ]
            );
        });
    }

    public function draft(): static
    {
        return $this->state([
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function published(): static
    {
        return $this->state([
            'is_published' => true,
            'published_at' => now()->subHour(),
        ]);
    }
}

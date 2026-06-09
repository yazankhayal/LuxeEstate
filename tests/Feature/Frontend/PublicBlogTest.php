<?php

namespace Tests\Feature\Frontend;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\CreatesUsers;
use Tests\TestCase;

class PublicBlogTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedLanguages();
    }

    /** @test */
    public function blog_listing_page_is_accessible(): void
    {
        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertInertia(fn ($p) => $p->component('Public/Blog/Index'));
    }

    /** @test */
    public function only_published_posts_appear_in_listing(): void
    {
        Post::factory()->count(3)->create([
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
        Post::factory()->count(2)->create([
            'is_published' => false,
            'published_at' => null,
        ]);

        $response = $this->get('/blog');

        $response->assertInertia(fn ($p) => $p->where('posts.total', 3));
    }

    /** @test */
    public function future_published_posts_do_not_appear(): void
    {
        Post::factory()->count(2)->create([
            'is_published' => true,
            'published_at' => now()->subHour(),
        ]);
        Post::factory()->create([
            'is_published' => true,
            'published_at' => now()->addDays(2),
        ]);

        $response = $this->get('/blog');

        $response->assertInertia(fn ($p) => $p->where('posts.total', 2));
    }

    /** @test */
    public function blog_listing_can_be_filtered_by_category(): void
    {
        $cat1 = Category::factory()->create(['slug' => 'market-cat-' . uniqid()]);
        $cat2 = Category::factory()->create(['slug' => 'tips-cat-'   . uniqid()]);

        Post::factory()->count(3)->create([
            'is_published' => true,
            'published_at' => now()->subDay(),
            'category_id'  => $cat1->id,
        ]);
        Post::factory()->count(2)->create([
            'is_published' => true,
            'published_at' => now()->subDay(),
            'category_id'  => $cat2->id,
        ]);

        $response = $this->get("/blog?category={$cat1->slug}");

        $response->assertInertia(fn ($p) => $p->where('posts.total', 3));
    }

    /** @test */
    public function single_blog_post_is_accessible_by_slug(): void
    {
        $post = Post::factory()->create([
            'is_published' => true,
            'published_at' => now()->subDay(),
            'slug'         => 'my-test-blog-post-' . uniqid(),
        ]);
        $post->translations()->create([
            'locale'  => 'en',
            'title'   => 'Test Post',
            'excerpt' => 'Excerpt',
            'content' => '<p>Content</p>',
        ]);

        $response = $this->get("/blog/{$post->slug}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($p) => $p->component('Public/Blog/Show'));
    }

    /** @test */
    public function unpublished_post_returns_404(): void
    {
        $post = Post::factory()->draft()->create([
            'slug' => 'draft-post-' . uniqid(),
        ]);

        $response = $this->get("/blog/{$post->slug}");

        $response->assertStatus(404);
    }

    /** @test */
    public function viewing_a_post_increments_view_count(): void
    {
        $post = Post::factory()->create([
            'is_published' => true,
            'published_at' => now()->subDay(),
            'slug'         => 'view-count-post-' . uniqid(),
            'views_count'  => 0,
        ]);
        $post->translations()->create([
            'locale'  => 'en',
            'title'   => 'View Count Test',
            'excerpt' => 'Excerpt',
            'content' => '<p>Content</p>',
        ]);

        $this->get("/blog/{$post->slug}");

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'views_count' => 1]);
    }
}

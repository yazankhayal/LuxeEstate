<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\CreatesUsers;
use Tests\TestCase;

class BlogAdminTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedLanguages();
    }

    /** @test */
    public function admin_can_view_blog_posts_list(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/blog');

        $response->assertStatus(200);
        $response->assertInertia(fn ($p) => $p->component('Admin/Blog/Index'));
    }

    /** @test */
    public function admin_can_create_a_blog_post(): void
    {
        $admin    = $this->createAdmin();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post('/admin/blog', [
            'slug'          => 'test-blog-post',
            'category_id'   => $category->id,
            'tag_ids'       => [],
            'is_published'  => true,
            'published_at'  => now()->toDateTimeString(),
            'translations'  => [
                'en' => [
                    'title'   => 'Test Blog Post',
                    'excerpt' => 'A short excerpt.',
                    'content' => 'Full article content goes here.',
                ],
            ],
        ]);

        $response->assertRedirect('/admin/blog');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('posts', [
            'slug'         => 'test-blog-post',
            'is_published' => true,
        ]);
        $this->assertDatabaseHas('post_translations', [
            'locale' => 'en',
            'title'  => 'Test Blog Post',
        ]);
    }

    /** @test */
    public function admin_can_update_a_blog_post(): void
    {
        $admin    = $this->createAdmin();
        $category = Category::factory()->create();
        $post     = Post::factory()->create(['category_id' => $category->id, 'slug' => 'orig-slug']);

        $response = $this->actingAs($admin)->put("/admin/blog/{$post->id}", [
            'slug'         => 'orig-slug',
            'category_id'  => $category->id,
            'tag_ids'      => [],
            'is_published' => true,
            'translations' => [
                'en' => ['title' => 'Updated Title', 'excerpt' => 'Updated excerpt', 'content' => 'Updated content'],
            ],
        ]);

        $response->assertRedirect('/admin/blog');
        $this->assertDatabaseHas('post_translations', ['post_id' => $post->id, 'title' => 'Updated Title']);
    }

    /** @test */
    public function admin_can_toggle_post_published_status(): void
    {
        $admin = $this->createAdmin();
        $post  = Post::factory()->create(['is_published' => true]);

        $this->actingAs($admin)->patch("/admin/blog/{$post->id}/toggle");

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'is_published' => false]);
    }

    /** @test */
    public function toggling_twice_restores_original_status(): void
    {
        $admin = $this->createAdmin();
        $post  = Post::factory()->create(['is_published' => true]);

        $this->actingAs($admin)->patch("/admin/blog/{$post->id}/toggle");
        $this->actingAs($admin)->patch("/admin/blog/{$post->id}/toggle");

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'is_published' => true]);
    }

    /** @test */
    public function admin_can_delete_a_post(): void
    {
        $admin = $this->createAdmin();
        $post  = Post::factory()->create();

        $response = $this->actingAs($admin)->delete("/admin/blog/{$post->id}");

        $response->assertRedirect('/admin/blog');
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    /** @test */
    public function post_english_title_is_required(): void
    {
        $admin    = $this->createAdmin();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post('/admin/blog', [
            'slug'         => 'no-title',
            'category_id'  => $category->id,
            'translations' => [
                'en' => ['title' => '', 'content' => 'Some content here'],
            ],
        ]);

        $response->assertSessionHasErrors('translations.en.title');
    }

    /** @test */
    public function post_slug_must_be_unique(): void
    {
        $admin    = $this->createAdmin();
        $category = Category::factory()->create();
        Post::factory()->create(['slug' => 'existing-slug']);

        $response = $this->actingAs($admin)->post('/admin/blog', [
            'slug'         => 'existing-slug',
            'category_id'  => $category->id,
            'translations' => ['en' => ['title' => 'Title', 'content' => 'Content here.']],
        ]);

        $response->assertSessionHasErrors('slug');
    }
}

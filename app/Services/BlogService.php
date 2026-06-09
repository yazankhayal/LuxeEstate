<?php

namespace App\Services;

use App\DTOs\Blog\CreatePostDTO;
use App\DTOs\Blog\UpdatePostDTO;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BlogService
{
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return Post::with(['translation', 'category', 'author'])
            ->published()
            ->when($filters['category'] ?? null, fn($q, $cat) =>
                $q->whereHas('category', fn($c) => $c->where('slug', $cat))
            )
            ->when($filters['tag'] ?? null, fn($q, $tag) =>
                $q->whereHas('tags', fn($t) => $t->where('slug', $tag))
            )
            ->when($filters['search'] ?? null, fn($q, $search) =>
                $q->whereHas('translations', fn($t) =>
                    $t->where('title', 'like', "%{$search}%")
                )
            )
            ->orderByDesc('published_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findBySlug(string $slug): Post
    {
        return Post::with(['translations', 'category', 'tags', 'author'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function create(CreatePostDTO $dto, int $authorId): Post
    {
        return DB::transaction(function () use ($dto, $authorId) {
            $post = Post::create([
                'slug'             => $dto->slug,
                'category_id'      => $dto->categoryId,
                'author_id'        => $authorId,
                'is_published'     => $dto->isPublished,
                'published_at'     => $dto->isPublished ? ($dto->publishedAt ?? now()) : null,
                'featured_image'   => $dto->featuredImage,
                'meta_title'       => $dto->metaTitle,
                'meta_description' => $dto->metaDescription,
                'meta_keywords'    => $dto->metaKeywords,
            ]);

            $post->tags()->sync($dto->tagIds);
            $this->syncTranslations($post, $dto->translations);

            return $post;
        });
    }

    public function update(UpdatePostDTO $dto): Post
    {
        return DB::transaction(function () use ($dto) {
            $post = Post::findOrFail($dto->id);

            if ($dto->featuredImage && $dto->featuredImage !== $post->featured_image) {
                if($post->featured_image && Storage::disk('public')->exists($post->featured_image)) {
                    Storage::disk('public')->delete($post->featured_image);
                }
            }

            $post->update([
                'slug'             => $dto->slug,
                'category_id'      => $dto->categoryId,
                'is_published'     => $dto->isPublished,
                'published_at'     => $dto->isPublished ? ($dto->publishedAt ?? $post->published_at ?? now()) : null,
                'featured_image'   => $dto->featuredImage ?? $post->featured_image,
                'meta_title'       => $dto->metaTitle,
                'meta_description' => $dto->metaDescription,
                'meta_keywords'    => $dto->metaKeywords,
            ]);

            $post->tags()->sync($dto->tagIds);
            $this->syncTranslations($post, $dto->translations);

            return $post->refresh();
        });
    }

    public function delete(int $id): void
    {
        $post = Post::findOrFail($id);

        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();
    }

    public function getRecent(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return Post::with(['translation', 'category'])
            ->published()
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    private function syncTranslations(Post $post, array $translations): void
    {
        foreach ($translations as $locale => $data) {
            $post->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'   => $data['title'] ?? '',
                    'excerpt' => $data['excerpt'] ?? '',
                    'content' => $data['content'] ?? '',
                ]
            );
        }
    }
}

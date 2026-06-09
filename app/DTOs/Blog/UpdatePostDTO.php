<?php

namespace App\DTOs\Blog;

final class UpdatePostDTO
{
    public function __construct(
        public readonly int     $id,
        public readonly string  $slug,
        public readonly int     $categoryId,
        public readonly array   $tagIds,
        public readonly bool    $isPublished,
        public readonly ?string $publishedAt,
        public readonly ?string $featuredImage,
        public readonly ?string $metaTitle,
        public readonly ?string $metaDescription,
        public readonly ?string $metaKeywords,
        public readonly array   $translations,
    ) {}

    public static function fromRequest(int $id, array $data): self
    {
        return new self(
            id:             $id,
            slug:           $data['slug'],
            categoryId:     (int) $data['category_id'],
            tagIds:         $data['tag_ids'] ?? [],
            isPublished:    (bool) ($data['is_published'] ?? false),
            publishedAt:    $data['published_at'] ?? null,
            featuredImage:  $data['featured_image'] ?? null,
            metaTitle:      $data['meta_title'] ?? null,
            metaDescription:$data['meta_description'] ?? null,
            metaKeywords:   $data['meta_keywords'] ?? null,
            translations:   $data['translations'] ?? [],
        );
    }
}

<?php

namespace App\DTOs\Service;

final class CreateServiceDTO
{
    public function __construct(
        public readonly string  $slug,
        public readonly string  $icon,
        public readonly int     $order,
        public readonly bool    $isActive,
        public readonly ?string $image,
        public readonly array   $translations, // ['en' => ['title'=>..,'description'=>..,'content'=>..]]
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            slug:         $data['slug'],
            icon:         $data['icon'] ?? 'home',
            order:        (int) ($data['order'] ?? 0),
            isActive:     (bool) ($data['is_active'] ?? true),
            image:        $data['image'] ?? null,
            translations: $data['translations'] ?? [],
        );
    }
}

final class UpdateServiceDTO
{
    public function __construct(
        public readonly int     $id,
        public readonly string  $slug,
        public readonly string  $icon,
        public readonly int     $order,
        public readonly bool    $isActive,
        public readonly ?string $image,
        public readonly array   $translations,
    ) {}

    public static function fromRequest(int $id, array $data): self
    {
        return new self(
            id:           $id,
            slug:         $data['slug'],
            icon:         $data['icon'] ?? 'home',
            order:        (int) ($data['order'] ?? 0),
            isActive:     (bool) ($data['is_active'] ?? true),
            image:        $data['image'] ?? null,
            translations: $data['translations'] ?? [],
        );
    }
}

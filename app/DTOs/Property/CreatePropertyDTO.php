<?php

namespace App\DTOs\Property;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;

final class CreatePropertyDTO
{
    public function __construct(
        public readonly string         $slug,
        public readonly PropertyType   $type,
        public readonly PropertyStatus $status,
        public readonly float          $price,
        public readonly string         $currency,
        public readonly string         $location,
        public readonly string         $city,
        public readonly string         $country,
        public readonly float          $area,
        public readonly ?int           $bedrooms,
        public readonly ?int           $bathrooms,
        public readonly bool           $isFeatured,
        public readonly ?float         $latitude,
        public readonly ?float         $longitude,
        public readonly ?string        $whatsapp,
        public readonly ?string        $phone,
        public readonly ?string        $videoUrl,
        // SEO
        public readonly ?string        $metaTitle,
        public readonly ?string        $metaDescription,
        public readonly ?string        $metaKeywords,
        // Translations keyed by locale
        public readonly array          $translations, // ['en' => ['title'=>..,'description'=>..], 'ar' => [...]]
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            slug:            $data['slug'],
            type:            PropertyType::from($data['type']),
            status:          PropertyStatus::from($data['status']),
            price:           (float) $data['price'],
            currency:        $data['currency'] ?? 'USD',
            location:        $data['location'],
            city:            $data['city'],
            country:         $data['country'],
            area:            (float) $data['area'],
            bedrooms:        isset($data['bedrooms']) ? (int) $data['bedrooms'] : null,
            bathrooms:       isset($data['bathrooms']) ? (int) $data['bathrooms'] : null,
            isFeatured:      (bool) ($data['is_featured'] ?? false),
            latitude:        isset($data['latitude']) ? (float) $data['latitude'] : null,
            longitude:       isset($data['longitude']) ? (float) $data['longitude'] : null,
            whatsapp:        $data['whatsapp'] ?? null,
            phone:           $data['phone'] ?? null,
            videoUrl:        $data['video_url'] ?? null,
            metaTitle:       $data['meta_title'] ?? null,
            metaDescription: $data['meta_description'] ?? null,
            metaKeywords:    $data['meta_keywords'] ?? null,
            translations:    $data['translations'] ?? [],
        );
    }
}

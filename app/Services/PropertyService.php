<?php

namespace App\Services;

use App\DTOs\Property\CreatePropertyDTO;
use App\DTOs\Property\UpdatePropertyDTO;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Services\Interfaces\PropertyServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PropertyService implements PropertyServiceInterface
{
    public function paginate(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        return Property::query()
            ->with(['translation', 'coverImage'])
            ->active()
            ->filter($filters)
            ->when(
                isset($filters['search']),
                fn($q) => $q->whereHas('translations', fn($t) =>
                    $t->where('title', 'like', "%{$filters['search']}%")
                )
            )
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findBySlug(string $slug): Property
    {
        return Property::with([
            'translations',
            'images',
            'contacts' => fn($q) => $q->limit(5),
        ])->where('slug', $slug)->firstOrFail();
    }

    public function create(CreatePropertyDTO $dto): Property
    {
        return DB::transaction(function () use ($dto) {
            $property = Property::create([
                'slug'            => $dto->slug,
                'type'            => $dto->type,
                'status'          => $dto->status,
                'price'           => $dto->price,
                'currency'        => $dto->currency,
                'location'        => $dto->location,
                'city'            => $dto->city,
                'country'         => $dto->country,
                'area'            => $dto->area,
                'bedrooms'        => $dto->bedrooms,
                'bathrooms'       => $dto->bathrooms,
                'is_featured'     => $dto->isFeatured,
                'latitude'        => $dto->latitude,
                'longitude'       => $dto->longitude,
                'whatsapp'        => $dto->whatsapp,
                'phone'           => $dto->phone,
                'video_url'       => $dto->videoUrl,
                'meta_title'      => $dto->metaTitle,
                'meta_description'=> $dto->metaDescription,
                'meta_keywords'   => $dto->metaKeywords,
            ]);

            $this->syncTranslations($property, $dto->translations);

            return $property;
        });
    }

    public function update(UpdatePropertyDTO $dto): Property
    {
        return DB::transaction(function () use ($dto) {
            $property = Property::findOrFail($dto->id);

            $property->update([
                'slug'            => $dto->slug,
                'type'            => $dto->type,
                'status'          => $dto->status,
                'price'           => $dto->price,
                'currency'        => $dto->currency,
                'location'        => $dto->location,
                'city'            => $dto->city,
                'country'         => $dto->country,
                'area'            => $dto->area,
                'bedrooms'        => $dto->bedrooms,
                'bathrooms'       => $dto->bathrooms,
                'is_featured'     => $dto->isFeatured,
                'latitude'        => $dto->latitude,
                'longitude'       => $dto->longitude,
                'whatsapp'        => $dto->whatsapp,
                'phone'           => $dto->phone,
                'video_url'       => $dto->videoUrl,
                'meta_title'      => $dto->metaTitle,
                'meta_description'=> $dto->metaDescription,
                'meta_keywords'   => $dto->metaKeywords,
            ]);

            $this->syncTranslations($property, $dto->translations);

            return $property->refresh();
        });
    }

    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $property = Property::findOrFail($id);

            // Delete images from storage
            foreach ($property->images as $image) {
                Storage::disk('public')->delete($image->path);
            }

            $property->delete();
        });
    }

    public function getFeatured(int $limit = 6): \Illuminate\Database\Eloquent\Collection
    {
        return Property::with(['translation', 'coverImage'])
            ->active()
            ->featured()
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function uploadImages(Property $property, array $images, bool $firstIsCover = true): void
    {
        $order = $property->images()->max('order') ?? 0;

        foreach ($images as $index => $image) {
            $path = $image->store("properties/{$property->id}", 'public');

            PropertyImage::create([
                'property_id' => $property->id,
                'path'        => $path,
                'is_cover'    => $firstIsCover && $index === 0 && ! $property->images()->where('is_cover', true)->exists(),
                'order'       => ++$order,
                'alt'         => $property->trans('title'),
            ]);
        }
    }

    public function deleteImage(int $imageId): void
    {
        $image = PropertyImage::findOrFail($imageId);
        Storage::disk('public')->delete($image->path);

        $wasCover = $image->is_cover;
        $propertyId = $image->property_id;

        $image->delete();

        // Reassign cover if needed
        if ($wasCover) {
            $next = PropertyImage::where('property_id', $propertyId)->first();
            $next?->update(['is_cover' => true]);
        }
    }

    public function reorderImages(array $orderedIds): void
    {
        foreach ($orderedIds as $order => $id) {
            PropertyImage::where('id', $id)->update(['order' => $order + 1]);
        }
    }

    private function syncTranslations(Property $property, array $translations): void
    {
        foreach ($translations as $locale => $data) {
            $property->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title'       => $data['title'] ?? '',
                    'description' => $data['description'] ?? '',
                    'address'     => $data['address'] ?? '',
                    'features'    => $data['features'] ?? [],
                ]
            );
        }
    }
}

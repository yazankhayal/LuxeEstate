<?php

namespace App\Services\Interfaces;

use App\DTOs\Property\CreatePropertyDTO;
use App\DTOs\Property\UpdatePropertyDTO;
use App\Models\Property;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PropertyServiceInterface
{
    public function paginate(array $filters = [], int $perPage = 12): LengthAwarePaginator;

    public function findBySlug(string $slug): Property;

    public function create(CreatePropertyDTO $dto): Property;

    public function update(UpdatePropertyDTO $dto): Property;

    public function delete(int $id): void;

    public function getFeatured(int $limit = 6): \Illuminate\Database\Eloquent\Collection;

    public function uploadImages(Property $property, array $images, bool $firstIsCover = true): void;

    public function deleteImage(int $imageId): void;

    public function reorderImages(array $orderedIds): void;
}

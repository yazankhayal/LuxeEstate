<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class MediaService
{
    /**
     * Store an uploaded file and optionally resize it.
     */
    public function store(
        UploadedFile $file,
        string $directory,
        ?int $maxWidth = 1920,
        ?int $maxHeight = 1080,
        int $quality = 85
    ): string {
        $path = $file->store($directory, 'public');
        return $path;
    }

    /**
     * Delete a file from public storage.
     */
    public function delete(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }

    /**
     * Store multiple files.
     */
    public function storeMany(array $files, string $directory): array
    {
        $paths = [];
        foreach ($files as $file) {
            $paths[] = $this->store($file, $directory);
        }
        return $paths;
    }

    /**
     * Get the public URL of a stored file.
     */
    public function url(string $path): string
    {
        return Storage::disk('public')->url($path);
    }
}

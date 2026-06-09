<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'locale_name'];

    protected $casts = [
        'locale_name' => 'array', // ['en' => 'Tech', 'ar' => 'تقنية', 'tr' => 'Teknoloji']
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function trans(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return $this->locale_name[$locale]
            ?? $this->locale_name[config('app.fallback_locale')]
            ?? $this->name;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function factory()
    {
        return CategoryFactory::new();
    }
}

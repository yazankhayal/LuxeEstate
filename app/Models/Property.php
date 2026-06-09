<?php

namespace App\Models;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use Database\Factories\PropertyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'type',
        'status',
        'price',
        'currency',
        'location',
        'city',
        'country',
        'area',
        'bedrooms',
        'bathrooms',
        'is_featured',
        'latitude',
        'longitude',
        'whatsapp',
        'phone',
        'video_url',
        'views_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'type'        => PropertyType::class,
        'status'      => PropertyStatus::class,
        'price'       => 'float',
        'area'        => 'float',
        'is_featured' => 'boolean',
        'latitude'    => 'float',
        'longitude'   => 'float',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function translations(): HasMany
    {
        return $this->hasMany(PropertyTranslation::class);
    }

    public function translation(): HasOne
    {
        return $this->hasOne(PropertyTranslation::class)
            ->where('locale', app()->getLocale());
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('order');
    }

    public function coverImage(): HasOne
    {
        return $this->hasOne(PropertyImage::class)
            ->where('is_cover', true)
            ->orderBy('order');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', PropertyStatus::Active);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeForSale($query)
    {
        return $query->where('type', PropertyType::Sale);
    }

    public function scopeForRent($query)
    {
        return $query->where('type', PropertyType::Rent);
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['type'] ?? null, fn($q, $type) => $q->where('type', $type))
            ->when($filters['city'] ?? null, fn($q, $city) => $q->where('city', $city))
            ->when($filters['min_price'] ?? null, fn($q, $min) => $q->where('price', '>=', $min))
            ->when($filters['max_price'] ?? null, fn($q, $max) => $q->where('price', '<=', $max))
            ->when($filters['bedrooms'] ?? null, fn($q, $beds) => $q->where('bedrooms', '>=', $beds))
            ->when($filters['bathrooms'] ?? null, fn($q, $baths) => $q->where('bathrooms', '>=', $baths))
            ->when($filters['min_area'] ?? null, fn($q, $area) => $q->where('area', '>=', $area))
            ->when($filters['max_area'] ?? null, fn($q, $area) => $q->where('area', '<=', $area));
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function getTranslation(string $locale): ?PropertyTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }

    public function trans(string $field, ?string $locale = null): string
    {
        // Use app() only when the container is available (feature tests / production)
        // Fall back to 'en' for unit tests where the container is not booted
        $locale = $locale ?? (function_exists('app') && app()->bound('config') ? app()->getLocale() : 'en');
        $fallback = function_exists('app') && app()->bound('config')
            ? config('app.fallback_locale', 'en')
            : 'en';

        $translation = $this->getTranslation($locale)
            ?? $this->getTranslation($fallback);

        return $translation?->$field ?? '';
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function newFactory()
    {
        return PropertyFactory::new();
    }
}

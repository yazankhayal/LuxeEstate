<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'property_id',
        'locale',
        'title',
        'description',
        'address',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}

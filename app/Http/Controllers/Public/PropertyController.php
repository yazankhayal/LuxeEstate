<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Services\PropertyService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropertyController extends Controller
{
    public function __construct(private readonly PropertyService $propertyService)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'type', 'city', 'min_price', 'max_price',
            'bedrooms', 'bathrooms', 'min_area', 'max_area', 'search',
        ]);

        $properties = $this->propertyService->paginate($filters, 12);

        // Distinct cities for filter dropdown
        $cities = Property::active()
            ->distinct('city')
            ->pluck('city')
            ->sort()
            ->values();

        return Inertia::render('Public/Properties/Index', [
            'properties' => $properties,
            'filters'    => $filters,
            'cities'     => $cities,
        ]);
    }

    public function show(Property $property)
    {
        $property->load(['translations', 'images', 'contacts']);
        $property->incrementViews();

        // Similar properties
        $similar = Property::with(['translation', 'coverImage'])
            ->active()
            ->where('type', $property->type)
            ->where('city', $property->city)
            ->where('id', '!=', $property->id)
            ->limit(4)
            ->get();

        return Inertia::render('Public/Properties/Show', [
            'property' => $property,
            'similar'  => $similar,
        ]);
    }
}

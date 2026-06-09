<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\Property\CreatePropertyDTO;
use App\DTOs\Property\UpdatePropertyDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePropertyRequest;
use App\Models\Language;
use App\Models\Property;
use App\Services\PropertyService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropertyController extends Controller
{
    public function __construct(private readonly PropertyService $propertyService) {}

    public function index(Request $request)
    {
        $properties = Property::with(['translation', 'coverImage'])
            ->when($request->search, fn($q) =>
                $q->whereHas('translations', fn($t) =>
                    $t->where('title', 'like', "%{$request->search}%")
                )
            )
            ->when($request->type,   fn($q, $v) => $q->where('type', $v))
            ->when($request->status, fn($q, $v) => $q->where('status', $v))
            ->when($request->city,   fn($q, $v) => $q->where('city', $v))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Properties/Index', [
            'properties' => $properties,
            'filters'    => $request->only(['type', 'status', 'city', 'search']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Properties/Form', [
            'languages' => Language::active()->get(),
        ]);
    }

    public function store(StorePropertyRequest $request)
    {
        $dto      = CreatePropertyDTO::fromRequest($request->validated());
        $property = $this->propertyService->create($dto);

        if ($request->hasFile('images')) {
            $this->propertyService->uploadImages($property, $request->file('images'));
        }

        return redirect()->route('admin.properties.index')
            ->with('success', 'Property created successfully.');
    }

    public function edit(int $id)
    {
        $property = Property::with(['translations', 'images'])->findOrFail($id);

        return Inertia::render('Admin/Properties/Form', [
            'property'  => $property,
            'languages' => Language::active()->get(),
        ]);
    }

    public function update(StorePropertyRequest $request, int $id)
    {
        $dto = UpdatePropertyDTO::fromRequest($id, $request->validated());
        $this->propertyService->update($dto);

        $property = Property::findOrFail($id);
        if ($request->hasFile('images')) {
            $this->propertyService->uploadImages($property, $request->file('images'));
        }

        return redirect()->route('admin.properties.index')
            ->with('success', 'Property updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->propertyService->delete($id);

        return redirect()->route('admin.properties.index')
            ->with('success', 'Property deleted successfully.');
    }

    public function destroyImage(int $imageId)
    {
        $this->propertyService->deleteImage($imageId);

        return back()->with('success', 'Image deleted.');
    }

    public function reorderImages(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        $this->propertyService->reorderImages($request->input('ids'));

        return response()->json(['ok' => true]);
    }
}

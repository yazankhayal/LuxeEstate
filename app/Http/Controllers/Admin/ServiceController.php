<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Service;
use App\Models\ServiceTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Services/Index', [
            'services' => Service::with('translations')->orderBy('order')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Services/Form', [
            'languages' => Language::active()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug'                        => 'required|string|unique:services,slug',
            'icon'                        => 'required|string|max:50',
            'order'                       => 'integer|min:0',
            'is_active'                   => 'boolean',
            'image'                       => 'nullable|file|image|max:2048',
            'translations'                => 'required|array',
            'translations.en.title'       => 'required|string|max:255',
            'translations.en.description' => 'nullable|string',
            'translations.en.content'     => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('services', 'public');
            }

            $service = Service::create([
                'slug'      => $validated['slug'],
                'icon'      => $validated['icon'],
                'order'     => $validated['order'] ?? 0,
                'is_active' => $validated['is_active'] ?? true,
                'image'     => $imagePath,
            ]);

            foreach ($validated['translations'] as $locale => $trans) {
                if (! empty($trans['title'])) {
                    ServiceTranslation::create([
                        'service_id'  => $service->id,
                        'locale'      => $locale,
                        'title'       => $trans['title'],
                        'description' => $trans['description'] ?? null,
                        'content'     => $trans['content'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function edit(int $id)
    {
        $service = Service::with('translations')->findOrFail($id);

        return Inertia::render('Admin/Services/Form', [
            'service'   => $service,
            'languages' => Language::active()->get(),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $service   = Service::findOrFail($id);

        $validated = $request->validate([
            'slug'                        => "required|string|unique:services,slug,{$id}",
            'icon'                        => 'required|string|max:50',
            'order'                       => 'integer|min:0',
            'is_active'                   => 'boolean',
            'image'                       => 'nullable|file|image|max:2048',
            'translations'                => 'required|array',
            'translations.en.title'       => 'required|string|max:255',
            'translations.en.description' => 'nullable|string',
            'translations.en.content'     => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $validated, $service) {
            $imagePath = $service->image;
            if ($request->hasFile('image')) {
                if ($imagePath) Storage::disk('public')->delete($imagePath);
                $imagePath = $request->file('image')->store('services', 'public');
            }

            $service->update([
                'slug'      => $validated['slug'],
                'icon'      => $validated['icon'],
                'order'     => $validated['order'] ?? $service->order,
                'is_active' => $validated['is_active'] ?? $service->is_active,
                'image'     => $imagePath,
            ]);

            foreach ($validated['translations'] as $locale => $trans) {
                $service->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'title'       => $trans['title'] ?? '',
                        'description' => $trans['description'] ?? null,
                        'content'     => $trans['content'] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(int $id)
    {
        $service = Service::findOrFail($id);
        if ($service->image) Storage::disk('public')->delete($service->image);
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        foreach ($request->ids as $order => $id) {
            Service::where('id', $id)->update(['order' => $order + 1]);
        }

        return response()->json(['ok' => true]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LanguageController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Languages/Index', [
            'languages' => Language::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Languages/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:5|unique:languages,code',
            'name'        => 'required|string|max:50',
            'native_name' => 'required|string|max:50',
            'direction'   => 'required|in:ltr,rtl',
            'flag'        => 'nullable|string|max:10',
            'is_active'   => 'boolean',
            'is_default'  => 'boolean',
        ]);

        if (! empty($validated['is_default'])) {
            Language::where('is_default', true)->update(['is_default' => false]);
        }

        Language::create($validated);

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language added.');
    }

    public function edit(int $id)
    {
        return Inertia::render('Admin/Languages/Form', [
            'language' => Language::findOrFail($id),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $language  = Language::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:50',
            'native_name' => 'required|string|max:50',
            'direction'   => 'required|in:ltr,rtl',
            'flag'        => 'nullable|string|max:10',
            'is_active'   => 'boolean',
            'is_default'  => 'boolean',
        ]);

        if (! empty($validated['is_default'])) {
            Language::where('is_default', true)->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $language->update($validated);

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language updated.');
    }

    public function destroy(int $id)
    {
        $language = Language::findOrFail($id);

        if ($language->is_default) {
            return back()->with('error', 'Cannot delete the default language.');
        }

        $language->delete();

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language removed.');
    }

    public function toggleActive(int $id)
    {
        $language = Language::findOrFail($id);
        $language->update(['is_active' => ! $language->is_active]);

        return back()->with('success', 'Language status updated.');
    }

    public function setDefault(int $id)
    {
        $language = Language::findOrFail($id);
        Language::where('is_default', true)->update(['is_default' => false]);
        $language->update(['is_default' => true, 'is_active' => true]);

        return back()->with('success', "{$language->name} is now the default language.");
    }
}

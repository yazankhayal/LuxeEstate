<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::withCount('posts')->orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug'        => 'required|string|unique:categories,slug',
            'name'        => 'required|string|max:100',
            'locale_name' => 'nullable|array',
        ]);

        return response()->json(Category::create($validated), 201);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'locale_name' => 'nullable|array',
        ]);

        $category->update($validated);

        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        if ($category->posts()->exists()) {
            return response()->json(['message' => 'Cannot delete category with posts.'], 422);
        }

        $category->delete();

        return response()->json(['ok' => true]);
    }
}

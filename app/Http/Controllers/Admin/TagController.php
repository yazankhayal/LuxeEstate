<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        return response()->json(Tag::withCount('posts')->orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|unique:tags,slug',
            'name' => 'required|string|max:100',
        ]);

        return response()->json(Tag::create($validated), 201);
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate(['name' => 'required|string|max:100']);
        $tag->update($validated);

        return response()->json($tag);
    }

    public function destroy(Tag $tag)
    {
        $tag->posts()->detach();
        $tag->delete();

        return response()->json(['ok' => true]);
    }
}

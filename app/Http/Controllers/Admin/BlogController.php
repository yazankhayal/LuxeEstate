<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\Blog\CreatePostDTO;
use App\DTOs\Blog\UpdatePostDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Models\Category;
use App\Models\Language;
use App\Models\Post;
use App\Models\Tag;
use App\Services\BlogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function __construct(private readonly BlogService $blogService) {}

    public function index(Request $request)
    {
        $posts = Post::with(['translation', 'category', 'author'])
            ->when($request->search, fn($q) =>
                $q->whereHas('translations', fn($t) =>
                    $t->where('title', 'like', "%{$request->search}%")
                )
            )
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Blog/Index', [
            'posts'   => $posts,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Blog/Form', [
            'categories' => Category::orderBy('name')->get(),
            'tags'       => Tag::orderBy('name')->get(),
            'languages'  => Language::active()->get(),
        ]);
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')
                ->store('blog', 'public');
        }

        $dto = CreatePostDTO::fromRequest($data);
        $this->blogService->create($dto, $request->user()->id);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Post created successfully.');
    }

    public function edit(int $id)
    {
        $post = Post::with(['translations', 'tags'])->findOrFail($id);

        return Inertia::render('Admin/Blog/Form', [
            'post'       => $post,
            'categories' => Category::orderBy('name')->get(),
            'tags'       => Tag::orderBy('name')->get(),
            'languages'  => Language::active()->get(),
        ]);
    }

    public function update(StorePostRequest $request, int $id)
    {
        $data = $request->validated();

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')
                ->store('blog', 'public');
        }

        $dto = UpdatePostDTO::fromRequest($id, $data);
        $this->blogService->update($dto);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->blogService->delete($id);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Post deleted.');
    }

    public function togglePublished(int $id)
    {
        $post = Post::findOrFail($id);
        $post->update([
            'is_published' => ! $post->is_published,
            'published_at' => ! $post->is_published ? ($post->published_at ?? now()) : $post->published_at,
        ]);

        return back()->with('success', $post->is_published ? 'Post published.' : 'Post set to draft.');
    }

}
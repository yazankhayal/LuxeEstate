<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Services\BlogService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function __construct(private readonly BlogService $blogService)
    {
    }

    public function index(Request $request)
    {
        $posts = $this->blogService->paginate(
            $request->only(['category', 'tag', 'search'])
        );

        return Inertia::render('Public/Blog/Index', [
            'posts'      => $posts,
            'categories' => Category::withCount('posts')->get(),
            'filters'    => $request->only(['category', 'tag', 'search']),
        ]);
    }

    public function show(Post $post)
    {
        if (
            ! $post->is_published ||
            is_null($post->published_at) ||
            $post->published_at->isFuture()
        ) {
            abort(404);
        }

        $post->load(['translations', 'category', 'tags', 'author']);
        $post->increment('views_count');

        $related = Post::with(['translation', 'category'])
            ->published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->limit(3)
            ->get();

        return Inertia::render('Public/Blog/Show', [
            'post'    => $post,
            'related' => $related,
        ]);
    }
}

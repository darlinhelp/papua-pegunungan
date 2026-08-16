<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Category $category): View
    {
        $posts = $category->posts()
            ->published()
            ->with('category')
            ->latest('published_at')
            ->paginate(12);

        return view('posts.index', [
            'category' => $category,
            'posts'    => $posts,
        ]);
    }

    public function featured(): View
    {
        $posts = Post::published()
            ->where('is_featured', true)
            ->with('category')
            ->latest('published_at')
            ->paginate(12);

        return view('posts.index', [
            'category' => null,
            'heading'  => 'Berita Pilihan',
            'posts'    => $posts,
        ]);
    }

    public function show(Post $post): View
    {
        abort_if($post->published_at === null || $post->published_at->isFuture(), 404);

        $post->load('category');

        // Hitungan baca tanpa menyentuh updated_at.
        $post->incrementQuietly('views');

        $terkait = Post::published()
            ->where('category_id', $post->category_id)
            ->whereKeyNot($post->getKey())
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('posts.show', compact('post', 'terkait'));
    }
}

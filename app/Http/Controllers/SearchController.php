<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $results = $q === ''
            ? null
            : Post::published()
                ->where(fn ($query) => $query
                    ->where('title', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%")
                    ->orWhere('body', 'like', "%{$q}%"))
                ->with('category')
                ->latest('published_at')
                ->paginate(12)
                ->withQueryString();

        return view('search', compact('q', 'results'));
    }
}

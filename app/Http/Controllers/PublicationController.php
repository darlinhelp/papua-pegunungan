<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\View\View;

class PublicationController extends Controller
{
    public function index(): View
    {
        $publications = Publication::latest('published_at')->paginate(12);

        return view('publications.index', compact('publications'));
    }

    public function show(Publication $publication): View
    {
        $lainnya = Publication::whereKeyNot($publication->getKey())
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('publications.show', compact('publication', 'lainnya'));
    }
}

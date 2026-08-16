<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $albums = Album::with('photos')->latest('published_at')->paginate(12);

        return view('gallery.index', compact('albums'));
    }

    public function show(Album $album): View
    {
        $album->load('photos');

        return view('gallery.show', compact('album'));
    }
}

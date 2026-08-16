<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(): View
    {
        $videos = Video::latest('published_at')->paginate(12);

        return view('videos.index', compact('videos'));
    }

    public function show(Video $video): View
    {
        $lainnya = Video::whereKeyNot($video->getKey())
            ->latest('published_at')->take(6)->get();

        return view('videos.show', compact('video', 'lainnya'));
    }
}

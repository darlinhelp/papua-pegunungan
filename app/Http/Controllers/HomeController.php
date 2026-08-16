<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Banner;
use App\Models\Post;
use App\Models\Publication;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(): View|Response
    {
        // Saat mengembangkan, halaman selalu dibangun ulang supaya perubahan
        // Blade langsung terlihat.
        if (! app()->isProduction()) {
            return $this->tampilan();
        }

        // Di produksi, yang disimpan adalah HTML jadinya — bukan objek Eloquent.
        // Menyimpan model ke cache berisiko: objek harus di-serialize lalu
        // dibaca ulang, dan begitu pembacaan gagal, halaman error. String HTML
        // tidak punya masalah itu.
        //
        // Cache dihapus otomatis begitu ada konten disimpan; lihat
        // AppServiceProvider.
        $html = Cache::remember(
            'beranda:html',
            now()->addMinutes(30),
            fn () => $this->tampilan()->render()
        );

        return response($html);
    }

    protected function tampilan(): View
    {
        // Tiap blok berita menampilkan 1 tulisan besar + 3 judul di sampingnya,
        // jadi cukup ambil 4 per kategori.
        return view('home', [
            'banners'    => Banner::live()->orderBy('order')->get(),
            'kabar'      => Post::published()->whereRelation('category', 'slug', 'kabar-dpw')
                ->with('category')->latest('published_at')->take(4)->get(),
            'dpd'        => Post::published()->whereRelation('category', 'slug', 'kabar-dpd')
                ->with('category')->latest('published_at')->take(4)->get(),
            'pilihan'    => Post::published()->where('is_featured', true)
                ->with('category')->latest('published_at')->take(4)->get(),
            'opini'      => Post::published()->ofType('opini')
                ->with('category')->latest('published_at')->take(3)->get(),
            'media'      => Post::published()->ofType('media')
                ->with('category')->latest('published_at')->take(4)->get(),
            'album'      => Album::with('photos')->latest('published_at')->first(),
            'video'      => Video::latest('published_at')->take(4)->get(),
            'terbitan'   => Publication::latest('published_at')->take(4)->get(),
        ]);
    }
}

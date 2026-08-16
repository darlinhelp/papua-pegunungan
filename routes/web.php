<?php

use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/cari', SearchController::class)->name('search');

Route::get('/berita-pilihan', [PostController::class, 'featured'])->name('posts.featured');
Route::get('/kategori/{category:slug}', [PostController::class, 'index'])->name('posts.index');
Route::get('/berita/{post:slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/galeri-foto', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/galeri-foto/{album:slug}', [GalleryController::class, 'show'])->name('gallery.show');

Route::get('/video', [VideoController::class, 'index'])->name('videos.index');
Route::get('/video/{video:slug}', [VideoController::class, 'show'])->name('videos.show');

Route::get('/publikasi', [PublicationController::class, 'index'])->name('publications.index');
Route::get('/publikasi/{publication:slug}', [PublicationController::class, 'show'])->name('publications.show');

// Halaman statis diberi awalan /halaman.
// Sengaja tidak dipasang di akar: rute tangkap-semua di akar akan menelan
// alamat lain seperti /admin milik panel Filament.
Route::get('/halaman/{page:slug}', [PageController::class, 'show'])->name('pages.show');

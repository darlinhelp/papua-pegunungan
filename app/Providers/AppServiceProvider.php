<?php

namespace App\Providers;

use App\Models\Album;
use App\Models\Banner;
use App\Models\Post;
use App\Models\Publication;
use App\Models\Setting;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Tanggal tampil dalam bahasa Indonesia: "Senin, 10 Agustus 2026".
        Carbon::setLocale('id');

        Paginator::useTailwind();

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Di luar produksi, query N+1 dicatat sebagai peringatan di storage/logs.
        // Sengaja tidak dilempar sebagai error supaya halaman tetap tampil.
        Model::preventLazyLoading(! $this->app->isProduction());

        Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
            Log::warning('Relasi dimuat malas: ' . $model::class . '::' . $relation);
        });

        // HTML beranda disimpan 30 menit di produksi. Pemantau ini membuangnya
        // begitu ada konten disimpan, jadi editor tidak perlu menunggu.
        $bersihkan = fn () => Cache::forget('beranda:html');

        foreach ([Post::class, Banner::class, Album::class, Video::class, Publication::class, Setting::class] as $model) {
            $model::saved($bersihkan);
            $model::deleted($bersihkan);
        }
    }
}

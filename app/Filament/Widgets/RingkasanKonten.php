<?php

namespace App\Filament\Widgets;

use App\Models\Album;
use App\Models\Banner;
use App\Models\Post;
use App\Models\Video;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RingkasanKonten extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $terbit = Post::whereNotNull('published_at')->where('published_at', '<=', now())->count();
        $draf   = Post::whereNull('published_at')->count();
        $jadwal = Post::where('published_at', '>', now())->count();

        return [
            Stat::make('Tulisan terbit', $terbit)
                ->description($draf > 0 ? "{$draf} masih draf" : 'Tidak ada draf')
                ->descriptionIcon($draf > 0 ? 'heroicon-m-pencil-square' : 'heroicon-m-check-circle')
                ->color($draf > 0 ? 'warning' : 'success'),

            Stat::make('Terjadwal', $jadwal)
                ->description('Akan terbit otomatis')
                ->descriptionIcon('heroicon-m-clock')
                ->color($jadwal > 0 ? 'info' : 'gray'),

            Stat::make('Spanduk aktif', Banner::live()->count())
                ->description('Tayang di beranda')
                ->descriptionIcon('heroicon-m-rectangle-group')
                ->color('primary'),

            Stat::make('Album & video', Album::count() . ' / ' . Video::count())
                ->description('Dokumentasi kegiatan')
                ->descriptionIcon('heroicon-m-photo')
                ->color('gray'),
        ];
    }
}

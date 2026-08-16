<?php

namespace App\Filament\Widgets;

use App\Models\OnlineVisitor;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PengunjungOnlineWidget extends StatsOverviewWidget
{
    protected static ?int $sort = -1;

    // Angkanya diperbarui sendiri tanpa perlu memuat ulang halaman —
    // masuk akal untuk sesuatu yang berubah tiap beberapa menit.
    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $batasWaktu = now()->subMinutes(5);

        $jumlahOnline = OnlineVisitor::where('terakhir', '>=', $batasWaktu)->count();

        return [
            Stat::make('Sedang mengakses situs', $jumlahOnline)
                ->description('Pengunjung yang membuka halaman dalam 5 menit terakhir')
                ->descriptionIcon('heroicon-m-signal')
                ->color($jumlahOnline > 0 ? 'success' : 'gray'),
        ];
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\DailyVisit;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RingkasanKunjungan extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $hariIni   = DailyVisit::whereKey(now()->toDateString())->value('jumlah') ?? 0;
        $kemarin   = DailyVisit::whereKey(now()->subDay()->toDateString())->value('jumlah') ?? 0;
        $bulanIni  = DailyVisit::whereBetween('tanggal', [now()->startOfMonth(), now()->endOfMonth()])->sum('jumlah');
        $totalSemua = DailyVisit::sum('jumlah');

        return [
            Stat::make('Kunjungan hari ini', number_format($hariIni, 0, ',', '.'))
                ->description($kemarin > 0
                    ? ($hariIni >= $kemarin ? 'Naik dari kemarin' : 'Turun dari kemarin')
                    : 'Belum ada data kemarin')
                ->descriptionIcon($hariIni >= $kemarin ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($hariIni >= $kemarin ? 'success' : 'gray'),

            Stat::make('Kunjungan bulan ini', number_format($bulanIni, 0, ',', '.'))
                ->description(now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('Total sepanjang waktu', number_format($totalSemua, 0, ',', '.'))
                ->description('Sejak situs pertama dicatat')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('primary'),
        ];
    }
}

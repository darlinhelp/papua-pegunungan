<?php

namespace App\Filament\Widgets;

use App\Models\KunjunganPerangkat;
use App\Support\Perangkat;
use Filament\Widgets\ChartWidget;

class KunjunganPerangkatWidget extends ChartWidget
{
    protected ?string $heading = 'Kunjungan Berdasarkan Perangkat';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = [
        'md' => 1,
    ];

    /** Rentang waktu tetap 30 hari — cukup untuk gambaran umum tanpa filter tambahan. */
    protected function getData(): array
    {
        $label = Perangkat::label();

        $jumlah = KunjunganPerangkat::query()
            ->where('tanggal', '>=', now()->subDays(30)->toDateString())
            ->selectRaw('perangkat, SUM(jumlah) as total')
            ->groupBy('perangkat')
            ->pluck('total', 'perangkat');

        // Urutan tetap: komputer, ponsel, tablet — supaya warnanya konsisten
        // tiap kali dasbor dibuka, bukan berubah-ubah sesuai data.
        $urutan = ['desktop', 'mobile', 'tablet'];

        return [
            'datasets' => [
                [
                    'label' => 'Kunjungan 30 hari terakhir',
                    'data' => array_map(fn (string $k) => (int) ($jumlah[$k] ?? 0), $urutan),
                    'backgroundColor' => ['#18181B', '#FE5000', '#A1A1AA'],
                ],
            ],
            'labels' => array_map(fn (string $k) => $label[$k], $urutan),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}

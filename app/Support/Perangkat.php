<?php

namespace App\Support;

/**
 * Deteksi jenis perangkat dari User-Agent browser.
 *
 * Sengaja pakai pencocokan kata kunci sederhana, bukan paket pihak
 * ketiga — cukup akurat untuk kebutuhan statistik ramai/tidaknya situs,
 * tanpa menambah dependensi baru ke proyek.
 */
class Perangkat
{
    /** @return 'mobile'|'tablet'|'desktop' */
    public static function deteksi(?string $userAgent): string
    {
        $ua = $userAgent ?? '';

        if (preg_match('/iPad|Tablet(?!.*Mobile)/i', $ua)) {
            return 'tablet';
        }

        if (preg_match('/Mobi|Android(?=.*Mobile)|iPhone|iPod|Windows Phone/i', $ua)) {
            return 'mobile';
        }

        return 'desktop';
    }

    /** @return array<string, string> untuk label di dasbor */
    public static function label(): array
    {
        return [
            'desktop' => 'Komputer',
            'mobile'  => 'Ponsel',
            'tablet'  => 'Tablet',
        ];
    }
}

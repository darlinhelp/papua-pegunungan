<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Tiga warna utama situs: aksen, latar bagian selang-seling, dan teks.
 *
 * Nilainya dicetak sebagai variabel CSS berformat "R G B" (tanpa koma),
 * mengikuti pola yang dipakai Tailwind untuk warna yang mendukung
 * modifier transparansi seperti bg-jingga/50. Karena itu warna disimpan
 * sebagai heks lalu dipecah jadi tiga angka di sini, bukan dicetak
 * langsung sebagai heks.
 */
class Warna
{
    public const AKSEN = '#FE5000';
    public const LATAR = '#F7F7F8';
    public const TEKS  = '#18181B';

    public static function aksen(): string
    {
        return Setting::get('warna_aksen', self::AKSEN);
    }

    public static function latar(): string
    {
        return Setting::get('warna_latar', self::LATAR);
    }

    public static function teks(): string
    {
        return Setting::get('warna_teks', self::TEKS);
    }

    /** "R G B" — dipakai langsung sebagai nilai variabel CSS. */
    public static function rgb(string $heks): string
    {
        $heks = ltrim($heks, '#');

        if (strlen($heks) === 3) {
            $heks = $heks[0].$heks[0].$heks[1].$heks[1].$heks[2].$heks[2];
        }

        if (! preg_match('/^[0-9a-fA-F]{6}$/', $heks)) {
            $heks = ltrim(self::AKSEN, '#');
        }

        return implode(' ', [
            hexdec(substr($heks, 0, 2)),
            hexdec(substr($heks, 2, 2)),
            hexdec(substr($heks, 4, 2)),
        ]);
    }

    /**
     * Versi lebih gelap dari warna heks, untuk keadaan hover.
     * $faktor 0.85 berarti 85% dari kecerahan asli.
     */
    public static function gelapkan(string $heks, float $faktor): string
    {
        $heks = ltrim($heks, '#');

        if (! preg_match('/^[0-9a-fA-F]{6}$/', $heks)) {
            return self::rgb(self::AKSEN);
        }

        $r = (int) round(hexdec(substr($heks, 0, 2)) * $faktor);
        $g = (int) round(hexdec(substr($heks, 2, 2)) * $faktor);
        $b = (int) round(hexdec(substr($heks, 4, 2)) * $faktor);

        return "{$r} {$g} {$b}";
    }

    // ══════════════════════════════════════════════════════════════
    //  Gaya warna pita atas (baris tipis paling atas berisi tagline)
    // ══════════════════════════════════════════════════════════════

    public const GAYA_PITA_BAWAAN = 'hitam';

    /** @return array<string, string> */
    public static function pilihanGayaPita(): array
    {
        return [
            'hitam'   => 'Hitam polos — bawaan',
            'gradasi' => 'Gradasi — warna aksen memudar ke hitam',
            'aksen'   => 'Warna aksen polos',
        ];
    }

    public static function gayaPita(): string
    {
        return Setting::get('gaya_pita_atas', self::GAYA_PITA_BAWAAN);
    }
}

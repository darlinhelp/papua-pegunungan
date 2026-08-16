<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Logo dan ukuran bilah kepala situs.
 *
 * Semua angka di sini dicetak sebagai variabel CSS di <head>, lalu dipakai
 * kelas .kop dan .logo-kop. Jadi mengubahnya cukup lewat panel admin —
 * tidak perlu menyunting Blade maupun build ulang.
 */
class Kop
{
    // Nilai bawaan, dipakai kalau pengaturannya belum pernah diisi.
    public const LOGO_TINGGI        = 44;   // piksel, saat halaman di atas
    public const LOGO_TINGGI_KECIL  = 36;   // piksel, setelah digulir
    public const KOP_TINGGI         = 80;
    public const KOP_TINGGI_KECIL   = 64;
    public const KOP_LEBAR          = 'isi';

    /** Berkas logo untuk bilah kepala (latar putih). */
    public static function logoUrl(): string
    {
        $jalur = Setting::get('logo_situs');

        return $jalur
            ? asset('storage/' . $jalur)
            : asset('img/logo-dpw.svg');
    }

    /**
     * Logo untuk footer (latar gelap).
     * Kalau tidak diisi, logo utama yang dipakai — jadi DPW bisa mulai
     * dengan satu berkas saja, lalu menambah versi putih belakangan.
     */
    public static function logoFooterUrl(): string
    {
        $jalur = Setting::get('logo_footer');

        return $jalur
            ? asset('storage/' . $jalur)
            : static::logoUrl();
    }

    /** @return array<string, string> pilihan lebar bilah kepala */
    public static function pilihanLebar(): array
    {
        return [
            'isi'     => 'Selebar isi halaman — 1200 px',
            'spanduk' => 'Selebar spanduk — 1340 px',
            'penuh'   => 'Penuh selebar layar',
        ];
    }

    public static function lebarCss(): string
    {
        return match (Setting::get('kop_lebar', self::KOP_LEBAR)) {
            'spanduk' => '1404px',
            'penuh'   => '100%',
            default   => '1264px',
        };
    }

    /** Membaca angka dari pengaturan, dijaga tetap dalam rentang yang masuk akal. */
    public static function angka(string $kunci, int $bawaan, int $min, int $maks): int
    {
        $nilai = Setting::get($kunci);

        if (! is_numeric($nilai)) {
            return $bawaan;
        }

        return max($min, min($maks, (int) $nilai));
    }

    public static function logoTinggi(): int
    {
        return static::angka('logo_tinggi', self::LOGO_TINGGI, 24, 96);
    }

    public static function logoTinggiKecil(): int
    {
        return static::angka('logo_tinggi_kecil', self::LOGO_TINGGI_KECIL, 20, 80);
    }

    public static function kopTinggi(): int
    {
        return static::angka('kop_tinggi', self::KOP_TINGGI, 56, 140);
    }

    public static function kopTinggiKecil(): int
    {
        return static::angka('kop_tinggi_kecil', self::KOP_TINGGI_KECIL, 44, 120);
    }

    // ══════════════════════════════════════════════════════════════
    //  Tema footer
    // ══════════════════════════════════════════════════════════════

    public const FOOTER_TEMA_BAWAAN = 'gelap';

    /** @return array<string, string> */
    public static function pilihanFooterTema(): array
    {
        return [
            'gelap'  => 'Gelap — putih di atas hijau tua (bawaan)',
            'terang' => 'Terang — gelap di atas putih, cocok untuk logo berwarna gelap',
        ];
    }

    public static function footerGelap(): bool
    {
        return Setting::get('footer_tema', self::FOOTER_TEMA_BAWAAN) === 'gelap';
    }
}

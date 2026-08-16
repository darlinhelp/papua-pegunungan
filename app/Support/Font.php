<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Pilihan font situs.
 *
 * Daftarnya sengaja tertutup — hanya font di sini yang boleh dipakai.
 * Nilainya dicetak langsung ke dalam CSS, jadi tidak boleh menerima
 * teks bebas dari pengguna.
 */
class Font
{
    public const BAWAAN = 'plus-jakarta-sans';

    public const SKALA_BAWAAN = 'normal';

    /**
     * @return array<string, array{nama: string, stack: string, google: ?string}>
     */
    public static function daftar(): array
    {
        return [
            'plus-jakarta-sans' => [
                'nama'   => 'Plus Jakarta Sans — modern, dipakai banyak situs Indonesia',
                'stack'  => "'Plus Jakarta Sans', system-ui, sans-serif",
                'google' => 'Plus+Jakarta+Sans:wght@400;600;700;800',
            ],
            'inter' => [
                'nama'   => 'Inter — netral, sangat terbaca di layar kecil',
                'stack'  => "'Inter', system-ui, sans-serif",
                'google' => 'Inter:wght@400;600;700;800',
            ],
            'figtree' => [
                'nama'   => 'Figtree — ramah, sedikit membulat',
                'stack'  => "'Figtree', system-ui, sans-serif",
                'google' => 'Figtree:wght@400;600;700;800',
            ],
            'poppins' => [
                'nama'   => 'Poppins — geometris, judul terasa tegas',
                'stack'  => "'Poppins', system-ui, sans-serif",
                'google' => 'Poppins:wght@400;600;700;800',
            ],
            'nunito-sans' => [
                'nama'   => 'Nunito Sans — lembut, enak untuk teks panjang',
                'stack'  => "'Nunito Sans', system-ui, sans-serif",
                'google' => 'Nunito+Sans:wght@400;600;700;800',
            ],
            'source-sans-3' => [
                'nama'   => 'Source Sans 3 — klasik, cocok gaya resmi',
                'stack'  => "'Source Sans 3', system-ui, sans-serif",
                'google' => 'Source+Sans+3:wght@400;600;700;900',
            ],
            'lato' => [
                'nama'   => 'Lato — hangat, banyak dipakai situs organisasi',
                'stack'  => "'Lato', system-ui, sans-serif",
                'google' => 'Lato:wght@400;700;900',
            ],
            'sistem' => [
                // Tidak mengunduh apa pun — halaman terbuka paling cepat.
                'nama'   => 'Font bawaan perangkat — paling ringan, tanpa unduhan',
                'stack'  => "system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif",
                'google' => null,
            ],
        ];
    }

    /** @return array{nama: string, stack: string, google: ?string} */
    public static function terpilih(): array
    {
        $kunci = Setting::get('font_situs', self::BAWAAN);
        $daftar = static::daftar();

        // Kalau nilainya tidak dikenal (mis. bekas pengaturan lama),
        // jatuh ke bawaan alih-alih mencetak CSS yang rusak.
        return $daftar[$kunci] ?? $daftar[self::BAWAAN];
    }

    /** @return array<string, string> untuk kolom pilihan di panel admin */
    public static function pilihan(): array
    {
        return array_map(fn (array $f) => $f['nama'], static::daftar());
    }

    // ══════════════════════════════════════════════════════════════
    //  Ukuran teks
    // ══════════════════════════════════════════════════════════════

    /**
     * Pengali ukuran dasar. Karena seluruh tampilan memakai satuan rem,
     * satu angka ini menggeser semua teks sekaligus tanpa merusak
     * perbandingan antara judul, isi, dan keterangan.
     *
     * Lebar halaman tetap dalam piksel, jadi yang membesar hanya teksnya —
     * kolomnya tidak ikut melar.
     *
     * @return array<string, array{nama: string, pengali: string}>
     */
    public static function daftarSkala(): array
    {
        return [
            'kecil' => [
                'nama'    => 'Kecil — lebih banyak muat di satu layar',
                'pengali' => '0.9375',   // 15px
            ],
            'normal' => [
                'nama'    => 'Normal — ukuran umum situs berita',
                'pengali' => '1',        // 16px
            ],
            'besar' => [
                'nama'    => 'Besar — lebih nyaman dibaca lama',
                'pengali' => '1.0625',   // 17px
            ],
            'sangat-besar' => [
                'nama'    => 'Sangat besar — untuk pembaca yang kesulitan melihat',
                'pengali' => '1.125',    // 18px
            ],
        ];
    }

    public static function skala(): string
    {
        $kunci  = Setting::get('skala_teks', self::SKALA_BAWAAN);
        $daftar = static::daftarSkala();

        return ($daftar[$kunci] ?? $daftar[self::SKALA_BAWAAN])['pengali'];
    }

    /** @return array<string, string> untuk kolom pilihan di panel admin */
    public static function pilihanSkala(): array
    {
        return array_map(fn (array $s) => $s['nama'], static::daftarSkala());
    }

    // ══════════════════════════════════════════════════════════════
    //  Ukuran nama situs (terpisah dari skala teks umum)
    // ══════════════════════════════════════════════════════════════

    public const NAMA_UKURAN_BAWAAN = 'sedang';

    /** @return array<string, array{nama: string, skala: string}> */
    public static function daftarUkuranNama(): array
    {
        return [
            'kecil'        => ['nama' => 'Kecil',           'skala' => '0.85'],
            'sedang'       => ['nama' => 'Sedang — bawaan', 'skala' => '1'],
            'besar'        => ['nama' => 'Besar',           'skala' => '1.2'],
            'sangat-besar' => ['nama' => 'Sangat besar',    'skala' => '1.4'],
        ];
    }

    public static function ukuranNama(): string
    {
        $kunci  = Setting::get('ukuran_nama_situs', self::NAMA_UKURAN_BAWAAN);
        $daftar = static::daftarUkuranNama();

        return ($daftar[$kunci] ?? $daftar[self::NAMA_UKURAN_BAWAAN])['skala'];
    }

    /** @return array<string, string> untuk kolom pilihan di panel admin */
    public static function pilihanUkuranNama(): array
    {
        return array_map(fn (array $u) => $u['nama'], static::daftarUkuranNama());
    }

    // ══════════════════════════════════════════════════════════════
    //  Ukuran judul, terpisah untuk beranda dan halaman artikel
    // ══════════════════════════════════════════════════════════════

    public const SKALA_JUDUL_BAWAAN = 'sedang';

    /** @return array<string, array{nama: string, skala: string}> */
    public static function daftarSkalaJudul(): array
    {
        return [
            'kecil'        => ['nama' => 'Kecil',            'skala' => '0.85'],
            'sedang'       => ['nama' => 'Sedang — bawaan',  'skala' => '1'],
            'besar'        => ['nama' => 'Besar',            'skala' => '1.15'],
            'sangat-besar' => ['nama' => 'Sangat besar',     'skala' => '1.3'],
        ];
    }

    public static function skalaJudulBeranda(): string
    {
        $kunci  = Setting::get('skala_judul_beranda', self::SKALA_JUDUL_BAWAAN);
        $daftar = static::daftarSkalaJudul();

        return ($daftar[$kunci] ?? $daftar[self::SKALA_JUDUL_BAWAAN])['skala'];
    }

    public static function skalaJudulArtikel(): string
    {
        $kunci  = Setting::get('skala_judul_artikel', self::SKALA_JUDUL_BAWAAN);
        $daftar = static::daftarSkalaJudul();

        return ($daftar[$kunci] ?? $daftar[self::SKALA_JUDUL_BAWAAN])['skala'];
    }

    /** @return array<string, string> untuk kolom pilihan di panel admin */
    public static function pilihanSkalaJudul(): array
    {
        return array_map(fn (array $u) => $u['nama'], static::daftarSkalaJudul());
    }
}

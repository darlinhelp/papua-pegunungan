<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Blok mana yang tampil di beranda.
 *
 * Urutannya tetap (mengikuti susunan pks.id yang sudah dipakai) — yang
 * bisa diatur pengurus hanya tampil-tidaknya tiap blok, lewat kotak
 * centang di panel admin. Reordering fisik sengaja tidak disediakan
 * di versi ini karena butuh membongkar beranda jadi beberapa berkas
 * terpisah; bisa menyusul kalau memang dibutuhkan.
 */
class Layout
{
    /** @return array<string, string> kunci blok => label di panel admin */
    public static function daftarBlokBeranda(): array
    {
        return [
            'kabar'      => 'Kabar DPW',
            'pilihan'    => 'Berita Pilihan',
            'foto_video' => 'Galeri Foto & Video',
            'dpd'        => 'Kabar DPD Kabupaten',
            'opini'      => 'Opini',
            'media'      => 'Kutipan Media',
            'publikasi'  => 'Publikasi Digital',
        ];
    }

    /** Bawaan: semua blok tampil. */
    public static function blokBawaan(): array
    {
        return array_keys(static::daftarBlokBeranda());
    }

    /** @return list<string> kunci blok yang sedang aktif ditampilkan */
    public static function blokAktif(): array
    {
        $tersimpan = json_decode(Setting::get('layout_beranda', ''), true);

        if (! is_array($tersimpan) || empty($tersimpan)) {
            return static::blokBawaan();
        }

        // Saring supaya kunci yang sudah tidak dikenal (mis. sisa dari versi
        // lama) tidak ikut lolos dan menyebabkan galat di beranda.
        return array_values(array_intersect($tersimpan, static::blokBawaan()));
    }

    // ══════════════════════════════════════════════════════════════
    //  Header
    // ══════════════════════════════════════════════════════════════

    public static function logoDiTengah(): bool
    {
        return Setting::get('layout_header_logo', 'kiri') === 'tengah';
    }

    public static function pitaAtasTampil(): bool
    {
        return Setting::get('layout_header_pita', 'tampil') !== 'sembunyi';
    }

    // ══════════════════════════════════════════════════════════════
    //  Daftar berita: grid atau list
    // ══════════════════════════════════════════════════════════════

    public const DAFTAR_BAWAAN = 'grid';

    /** @return array<string, string> */
    public static function pilihanDaftar(): array
    {
        return [
            'grid' => 'Kartu (grid) — foto besar, cocok untuk berita bergambar',
            'list' => 'Daftar (list) — ringkas, cocok kalau berita banyak',
        ];
    }

    public static function tampilanDaftar(): string
    {
        return Setting::get('layout_daftar', self::DAFTAR_BAWAAN);
    }

    // ══════════════════════════════════════════════════════════════
    //  Gaya tautan judul saat disentuh (hover)
    // ══════════════════════════════════════════════════════════════

    public const GAYA_TAUTAN_BAWAAN = 'warna';

    /** @return array<string, string> */
    public static function pilihanGayaTautan(): array
    {
        return [
            'warna'        => 'Warna saja — teks berubah oranye',
            'garis'        => 'Garis bawah dari kiri — bergerak saat disentuh',
            'garis-tengah' => 'Garis bawah dari tengah — melebar ke dua sisi',
        ];
    }

    public static function gayaTautan(): string
    {
        return Setting::get('gaya_tautan_judul', self::GAYA_TAUTAN_BAWAAN);
    }
}

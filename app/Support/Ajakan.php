<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Lima kartu ajakan di bagian bawah beranda (Gabung, Sumbangan, dst).
 *
 * Disimpan sebagai teks JSON di satu kolom pengaturan — pola yang sama
 * dengan layout_beranda — karena isinya berupa daftar, bukan nilai
 * tunggal seperti kebanyakan pengaturan lain.
 */
class Ajakan
{
    /** Dipakai kalau pengaturan belum pernah diisi. */
    public static function bawaan(): array
    {
        return [
            ['baris1' => 'GABUNG', 'baris2' => 'DPW', 'ikon' => 'orang',
                'isi' => 'Bergabung menjadi anggota dan berkhidmat membangun tanah pegunungan.',
                'href' => 'https://daftar.pks.id', 'luar' => true],
            ['baris1' => 'SUMBANGAN', 'baris2' => 'DPW', 'ikon' => 'donasi',
                'isi' => 'Berkontribusi dalam program dakwah dan pelayanan wilayah.',
                'href' => '/halaman/sumbangan', 'luar' => false],
            ['baris1' => 'WEBSITE', 'baris2' => 'DPD', 'ikon' => 'globe',
                'isi' => 'Laman resmi pengurus daerah di delapan kabupaten se-provinsi.',
                'href' => '/halaman/struktur-pengurus', 'luar' => false],
            ['baris1' => 'INFO', 'baris2' => 'PUBLIK', 'ikon' => 'dokumen',
                'isi' => 'Formulir permohonan informasi publik sesuai keterbukaan partai.',
                'href' => '/halaman/info-publik', 'luar' => false],
            ['baris1' => 'UNDUH', 'baris2' => 'ARSIP', 'ikon' => 'unduh',
                'isi' => 'Arsip, dokumen, logo, dan materi resmi partai.',
                'href' => 'https://pks.id/download/', 'luar' => true],
        ];
    }

    /** @return list<array{baris1: string, baris2: string, isi: string, href: string, luar: bool}> */
    public static function daftar(): array
    {
        $tersimpan = json_decode(Setting::get('kartu_ajakan', ''), true);

        return is_array($tersimpan) && $tersimpan !== [] ? $tersimpan : static::bawaan();
    }
}

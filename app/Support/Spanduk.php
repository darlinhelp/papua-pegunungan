<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Perbandingan tinggi–lebar spanduk beranda.
 *
 * Lebar acuan 1340 piksel, sama dengan yang dipakai pks.id — angka itu
 * terbaca dari alamat gambarnya (resize_w/1340). Tingginya tidak tercantum
 * di sana, jadi disediakan beberapa pilihan yang lazim, plus pilihan
 * mengikuti ukuran gambar apa adanya.
 */
class Spanduk
{
    public const LEBAR = 1340;

    public const BAWAAN = 'auto';

    /**
     * @return array<string, array{nama: string, rasio: ?string, tinggi: ?int}>
     */
    public static function daftarRasio(): array
    {
        return [
            'auto' => [
                'nama'   => 'Ikuti ukuran gambar — sama seperti pks.id (disarankan)',
                'rasio'  => null,
                'tinggi' => null,
            ],
            '1340x300' => [
                'nama'   => '1340 × 300 — pita tipis',
                'rasio'  => '1340 / 300',
                'tinggi' => 300,
            ],
            '1340x400' => [
                'nama'   => '1340 × 400 — pita sedang',
                'rasio'  => '1340 / 400',
                'tinggi' => 400,
            ],
            '1340x503' => [
                'nama'   => '1340 × 503 — 8:3, umum untuk spanduk portal',
                'rasio'  => '1340 / 503',
                'tinggi' => 503,
            ],
            '1340x558' => [
                'nama'   => '1340 × 558 — 2,4:1, gaya sinema',
                'rasio'  => '1340 / 558',
                'tinggi' => 558,
            ],
            '1340x670' => [
                'nama'   => '1340 × 670 — 2:1',
                'rasio'  => '1340 / 670',
                'tinggi' => 670,
            ],
            '1340x754' => [
                'nama'   => '1340 × 754 — 16:9, paling tinggi',
                'rasio'  => '1340 / 754',
                'tinggi' => 754,
            ],
        ];
    }

    /** @return array{nama: string, rasio: ?string, tinggi: ?int} */
    public static function terpilih(): array
    {
        $kunci  = Setting::get('rasio_spanduk', self::BAWAAN);
        $daftar = static::daftarRasio();

        return $daftar[$kunci] ?? $daftar[self::BAWAAN];
    }

    /** Rasio untuk layar lebar; null berarti ikut ukuran gambar. */
    public static function rasio(): ?string
    {
        return static::terpilih()['rasio'];
    }

    /** Tinggi acuan, dipakai untuk atribut height pada tag gambar. */
    public static function tinggi(): ?int
    {
        return static::terpilih()['tinggi'];
    }

    /** Anjuran ukuran yang ditampilkan di panel admin. */
    public static function anjuran(): string
    {
        $tinggi = static::tinggi();

        return $tinggi
            ? self::LEBAR . ' × ' . $tinggi . ' px'
            : 'lebar ' . self::LEBAR . ' px, tinggi bebas (samakan antar-spanduk)';
    }

    /** @return array<string, string> untuk kolom pilihan di panel admin */
    public static function pilihan(): array
    {
        return array_map(fn (array $r) => $r['nama'], static::daftarRasio());
    }
}

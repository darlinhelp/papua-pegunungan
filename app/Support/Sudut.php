<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Kelengkungan sudut gambar dan kartu.
 *
 * Nilainya dicetak sebagai satu variabel CSS, lalu dipakai oleh kelas
 * .bingkai dan .kartu. Jadi satu pilihan mengubah seluruh situs sekaligus —
 * sampul berita, foto album, thumbnail video, sampul publikasi, spanduk,
 * dan gambar di dalam artikel.
 */
class Sudut
{
    public const BAWAAN = 'sedang';

    /**
     * @return array<string, array{nama: string, nilai: string}>
     */
    public static function daftar(): array
    {
        return [
            'tajam' => [
                'nama'  => 'Siku — tanpa lengkung sama sekali',
                'nilai' => '0px',
            ],
            'halus' => [
                'nama'  => 'Halus — lengkung tipis, nyaris tak terlihat',
                'nilai' => '4px',
            ],
            'sedang' => [
                'nama'  => 'Sedang — lengkung wajar, mirip pks.id',
                'nilai' => '10px',
            ],
            'bulat' => [
                'nama'  => 'Bulat — lengkung tegas',
                'nilai' => '16px',
            ],
            'sangat-bulat' => [
                'nama'  => 'Sangat bulat — kesan lembut',
                'nilai' => '24px',
            ],
        ];
    }

    public static function nilai(): string
    {
        $kunci  = Setting::get('sudut_gambar', self::BAWAAN);
        $daftar = static::daftar();

        return ($daftar[$kunci] ?? $daftar[self::BAWAAN])['nilai'];
    }

    /** @return array<string, string> untuk kolom pilihan di panel admin */
    public static function pilihan(): array
    {
        return array_map(fn (array $s) => $s['nama'], static::daftar());
    }
}

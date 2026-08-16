<?php

namespace App\Support;

/**
 * Kumpulan ikon garis untuk penanda kategori dan seksi.
 *
 * Ditulis langsung sebagai jalur SVG, bukan paket ikon, supaya tidak ada
 * berkas tambahan yang perlu diunduh pengunjung. Semuanya viewBox 24×24
 * dan bergaya garis, jadi tampil serasi berdampingan.
 *
 * Menambah ikon baru: cukup tambahkan satu baris di sini — pilihannya
 * ikut muncul sendiri di panel admin.
 */
class Ikon
{
    /** @return array<string, array{nama: string, jalur: string}> */
    public static function daftar(): array
    {
        return [
            'berita' => [
                'nama'  => 'Koran',
                'jalur' => 'M4 5.5h11.5v13H6.5a2.5 2.5 0 0 1-2.5-2.5V5.5zM15.5 9.5H19a1 1 0 0 1 1 1V16a2.5 2.5 0 0 1-2.5 2.5M7 8.5h6M7 11.5h6M7 14.5h4',
            ],
            'pengumuman' => [
                'nama'  => 'Pengeras suara',
                'jalur' => 'M4 10.5v3a1 1 0 0 0 1 1h2l4.5 3.5V6L7 9.5H5a1 1 0 0 0-1 1zM15 9.5a4 4 0 0 1 0 5M17.5 7a7.5 7.5 0 0 1 0 10',
            ],
            'kutipan' => [
                'nama'  => 'Tanda kutip',
                'jalur' => 'M7.5 15.5a3 3 0 1 1 0-6 3 3 0 0 1 3 3c0 3-2 5.2-4.5 6M17 15.5a3 3 0 1 1 0-6 3 3 0 0 1 3 3c0 3-2 5.2-4.5 6',
            ],
            'gagasan' => [
                'nama'  => 'Bola lampu',
                'jalur' => 'M9.5 18h5M10 21h4M12 3a6 6 0 0 0-3.5 10.9V15h7v-1.1A6 6 0 0 0 12 3z',
            ],
            'foto' => [
                'nama'  => 'Foto',
                'jalur' => 'M3.5 6.5h17v11h-17v-11zM3.5 15l4.5-4 3.5 3 3-2.5 6 5M15.5 9.5h.01',
            ],
            'video' => [
                'nama'  => 'Video',
                'jalur' => 'M3.5 6.5h11v11h-11v-11zM14.5 10.5l6-3v9l-6-3v-3z',
            ],
            'buku' => [
                'nama'  => 'Buku',
                'jalur' => 'M4.5 4.5h9a2.5 2.5 0 0 1 2.5 2.5v12.5H7a2.5 2.5 0 0 1-2.5-2.5v-12.5zM16 7h3.5v12.5H16M8 8.5h5M8 11.5h5',
            ],
            'orang' => [
                'nama'  => 'Pengurus',
                'jalur' => 'M9.5 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM3.5 19c0-3 2.7-5 6-5s6 2 6 5M16.5 6.2a2.8 2.8 0 0 1 0 5.4M18 14.3c1.7.7 2.8 2.1 2.8 4.2',
            ],
            'lembaga' => [
                'nama'  => 'Gedung',
                'jalur' => 'M3.5 20h17M5.5 20V8.5L12 4l6.5 4.5V20M9.5 20v-5h5v5M9 11h.01M15 11h.01',
            ],
            'peta' => [
                'nama'  => 'Lokasi',
                'jalur' => 'M12 21s6.5-5.6 6.5-10.5a6.5 6.5 0 1 0-13 0C5.5 15.4 12 21 12 21zM12 13a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z',
            ],
            'kalender' => [
                'nama'  => 'Agenda',
                'jalur' => 'M4 6.5h16v14H4v-14zM8 3.5v5M16 3.5v5M4 11h16M9 15h2M15 15h.01',
            ],
            'bintang' => [
                'nama'  => 'Sorotan',
                'jalur' => 'M12 3.5l2.6 5.5 5.9.8-4.3 4.2 1 6-5.2-2.9-5.2 2.9 1-6L3.5 9.8l5.9-.8L12 3.5z',
            ],
            'dokumen' => [
                'nama'  => 'Dokumen',
                'jalur' => 'M13.5 3.5H6.5v17h11V7.5l-4-4zM13.5 3.5v4h4M9 12.5h6M9 16h4',
            ],
            'obrolan' => [
                'nama'  => 'Sikap & pernyataan',
                'jalur' => 'M20 12c0 4-3.6 7-8 7-1 0-2-.2-2.9-.5L4 20l1.6-4A6.6 6.6 0 0 1 4 12c0-4 3.6-7 8-7s8 3 8 7zM9 11.5h.01M12 11.5h.01M15 11.5h.01',
            ],
            'perisai' => [
                'nama'  => 'Anti hoaks',
                'jalur' => 'M12 3.5l7 2.8v5.4c0 4.2-2.9 7.6-7 8.8-4.1-1.2-7-4.6-7-8.8V6.3l7-2.8zM9.2 12.1l2 2 3.6-3.8',
            ],
            'unduh' => [
                'nama'  => 'Unduh',
                'jalur' => 'M12 4v10m0 0l-4-4m4 4l4-4M4.5 18.5h15',
            ],
            'donasi' => [
                'nama'  => 'Donasi & sumbangan',
                'jalur' => 'M12 20s-7-4.3-9-8.7C1.6 7.8 4 4.8 7 5.3c2 .3 3.7 1.8 5 3.4 1.3-1.6 3-3.1 5-3.4 3-.5 5.4 2.5 4 6-2 4.4-9 8.7-9 8.7z',
            ],
            'globe' => [
                'nama'  => 'Situs & tautan',
                'jalur' => 'M12 20.5a8.5 8.5 0 1 0 0-17 8.5 8.5 0 0 0 0 17zM3.5 12h17M12 3.5c2.2 2.3 3.4 5.3 3.4 8.5S14.2 18.2 12 20.5c-2.2-2.3-3.4-5.3-3.4-8.5S9.8 5.8 12 3.5z',
            ],
        ];
    }

    public static function jalur(?string $nama): ?string
    {
        return static::daftar()[$nama]['jalur'] ?? null;
    }

    /** @return array<string, string> untuk kolom pilihan di panel admin */
    public static function pilihan(): array
    {
        return array_map(fn (array $i) => $i['nama'], static::daftar());
    }
}

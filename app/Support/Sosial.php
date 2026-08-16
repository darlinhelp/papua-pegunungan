<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Daftar kanal media sosial DPW.
 *
 * URL-nya disimpan di tabel settings dan diatur lewat panel admin
 * (Pengaturan → Identitas Situs). Kanal yang URL-nya kosong tidak
 * ditampilkan sama sekali — jadi DPW bisa mulai dengan dua kanal saja
 * tanpa ikon mati bertebaran di header.
 */
class Sosial
{
    /**
     * Definisi semua kanal yang didukung.
     * Kuncinya dipakai sebagai nama kolom pengaturan: sosial_instagram, dst.
     *
     * @return array<string, array{nama: string, ikon: string, contoh: string}>
     */
    public static function daftar(): array
    {
        return [
            'instagram' => [
                'nama'   => 'Instagram',
                'contoh' => 'https://instagram.com/pkspapuapegunungan',
                'ikon'   => 'M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.8.2 2.2.4.6.2 1 .5 1.4.9.4.4.7.8.9 1.4.2.4.4 1 .4 2.2.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.2 1.8-.4 2.2-.2.6-.5 1-.9 1.4-.4.4-.8.7-1.4.9-.4.2-1 .4-2.2.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-1.8-.2-2.2-.4-.6-.2-1-.5-1.4-.9-.4-.4-.7-.8-.9-1.4-.2-.4-.4-1-.4-2.2C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.2-1.8.4-2.2.2-.6.5-1 .9-1.4.4-.4.8-.7 1.4-.9.4-.2 1-.4 2.2-.4C8.4 2.2 8.8 2.2 12 2.2zm0 3.4a6.4 6.4 0 100 12.8 6.4 6.4 0 000-12.8zm0 10.6a4.2 4.2 0 110-8.4 4.2 4.2 0 010 8.4zm6.6-10.9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z',
            ],
            'youtube' => [
                'nama'   => 'YouTube',
                'contoh' => 'https://youtube.com/@pkspapuapegunungan',
                'ikon'   => 'M21.6 7.2c-.2-.9-.9-1.6-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4c-.9.2-1.6.9-1.8 1.8C2 8.8 2 12 2 12s0 3.2.4 4.8c.2.9.9 1.6 1.8 1.8C5.8 19 12 19 12 19s6.2 0 7.8-.4c.9-.2 1.6-.9 1.8-1.8.4-1.6.4-4.8.4-4.8s0-3.2-.4-4.8zM10 15.1V8.9l5.2 3.1-5.2 3.1z',
            ],
            'facebook' => [
                'nama'   => 'Facebook',
                'contoh' => 'https://facebook.com/pkspapuapegunungan',
                'ikon'   => 'M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.25-1.5 1.55-1.5H16.7V3.6c-.3 0-1.3-.1-2.45-.1-2.4 0-4.05 1.5-4.05 4.2v2.2H7.5V13h2.7v8h3.3z',
            ],
            'tiktok' => [
                'nama'   => 'TikTok',
                'contoh' => 'https://tiktok.com/@pkspapuapegunungan',
                'ikon'   => 'M16.5 3c.3 2.1 1.5 3.4 3.5 3.6v2.4c-1.2.1-2.3-.2-3.5-.9v5.6c0 4.4-4.1 6.9-7.6 5-2.3-1.2-3.1-4.1-2.1-6.4.9-2 3-3.1 5.3-2.7v2.6c-.4-.1-.8-.1-1.2 0-1.2.2-1.9 1.3-1.7 2.5.2 1.2 1.3 2 2.5 1.8 1.1-.2 1.9-1.1 1.9-2.3V3h2.9z',
            ],
            'x' => [
                'nama'   => 'X',
                'contoh' => 'https://x.com/pkspapeg',
                'ikon'   => 'M17.2 3h3.3l-7.2 8.2L21.8 21h-6.6l-5.2-6.7L4.1 21H.8l7.7-8.8L.5 3h6.8l4.7 6.2L17.2 3zm-1.2 16h1.8L8.1 4.9H6.1L16 19z',
            ],
            'whatsapp' => [
                'nama'   => 'WhatsApp',
                'contoh' => 'https://wa.me/62969xxxxxxx',
                'ikon'   => 'M12 2a10 10 0 00-8.6 15L2 22l5.2-1.4A10 10 0 1012 2zm0 2a8 8 0 016.7 12.3l-.3.5.6 2.1-2.2-.6-.5.3A8 8 0 1112 4zm-3.4 3.9c-.2 0-.5 0-.7.4-.2.4-.9.9-.9 2.1s.9 2.4 1 2.6c.1.2 1.7 2.7 4.2 3.7 2 .8 2.4.6 2.9.6.5 0 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2l-.6-.3-1.5-.7c-.2-.1-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1-.2-.1-1-.4-1.9-1.2-.7-.6-1.2-1.4-1.3-1.6-.1-.2 0-.4.1-.5l.4-.5c.1-.2.2-.3.2-.5l-.1-.5-.7-1.6c-.2-.4-.4-.4-.5-.4h-.4z',
            ],
            'telegram' => [
                'nama'   => 'Telegram',
                'contoh' => 'https://t.me/pkspapuapegunungan',
                'ikon'   => 'M21.9 4.3l-3 14.2c-.2 1-.8 1.3-1.7.8l-4.6-3.4-2.2 2.1c-.2.2-.5.5-1 .5l.3-4.7 8.6-7.8c.4-.3-.1-.5-.6-.2L6.1 12.6l-4.5-1.4c-1-.3-1-1 .2-1.5l17.6-6.8c.8-.3 1.5.2 1.2 1.4z',
            ],
        ];
    }

    /**
     * Hanya kanal yang sudah diisi URL-nya, siap dipakai di Blade.
     *
     * @return list<array{nama: string, url: string, ikon: string}>
     */
    public static function aktif(): array
    {
        $hasil = [];

        foreach (static::daftar() as $kunci => $kanal) {
            $url = Setting::get('sosial_' . $kunci);

            if (blank($url)) {
                continue;
            }

            $hasil[] = [
                'nama' => $kanal['nama'],
                'url'  => $url,
                'ikon' => $kanal['ikon'],
            ];
        }

        return $hasil;
    }
}

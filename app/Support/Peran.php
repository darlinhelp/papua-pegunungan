<?php

namespace App\Support;

/**
 * Tiga peran pengguna panel admin.
 *
 * Administrator — akses penuh: konten, spanduk, menu, pengaturan situs,
 *   dan manajemen pengguna.
 * Editor — bisa mengelola semua jenis konten (berita, spanduk, album,
 *   video, publikasi, halaman, menu), tapi tidak bisa membuka Pengaturan
 *   Situs atau mengelola pengguna lain.
 * Penulis — hanya bisa menulis dan menyunting beritanya SENDIRI. Tidak
 *   bisa melihat atau mengubah berita orang lain, dan tidak punya akses
 *   ke resource lain sama sekali (spanduk, kategori, halaman, dst).
 */
class Peran
{
    public const ADMIN  = 'administrator';
    public const EDITOR = 'editor';
    public const PENULIS = 'penulis';

    /** @return array<string, string> untuk kolom pilihan di panel admin */
    public static function daftar(): array
    {
        return [
            self::ADMIN   => 'Administrator — akses penuh',
            self::EDITOR  => 'Editor — kelola semua konten',
            self::PENULIS => 'Penulis — hanya berita sendiri',
        ];
    }

    public static function label(string $peran): string
    {
        return match ($peran) {
            self::ADMIN   => 'Administrator',
            self::EDITOR  => 'Editor',
            self::PENULIS => 'Penulis',
            default       => $peran,
        };
    }

    /** Warna lencana di tabel pengguna. */
    public static function warna(string $peran): string
    {
        return match ($peran) {
            self::ADMIN   => 'danger',
            self::EDITOR  => 'warning',
            self::PENULIS => 'gray',
            default       => 'gray',
        };
    }
}

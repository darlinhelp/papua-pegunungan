<?php

namespace App\Filament\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * Ditempel ke Resource yang hanya boleh dikelola Administrator dan Editor —
 * semua resource konten selain berita (kategori, spanduk, album, video,
 * publikasi, halaman, menu).
 *
 * Penulis tidak melihat menu resource ini sama sekali di sidebar, karena
 * canViewAny() ikut menentukan tampil-tidaknya item navigasi.
 */
trait HanyaAdminDanEditor
{
    public static function canViewAny(): bool
    {
        return auth()->user()?->bisaKelolaKontenLain() ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->bisaKelolaKontenLain() ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->bisaKelolaKontenLain() ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->bisaKelolaKontenLain() ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->bisaKelolaKontenLain() ?? false;
    }
}

<?php

namespace App\Filament\RichContent;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Illuminate\Support\Facades\Storage;

/**
 * Satu gambar dengan keterangan di bawahnya.
 *
 * Tombol lampiran bawaan editor hanya menyisipkan gambar polos tanpa
 * keterangan. Blok ini menggantikannya untuk gambar yang perlu caption.
 */
class GambarBlock extends RichContentCustomBlock
{
    public static function getId(): string
    {
        return 'gambar';
    }

    public static function getLabel(): string
    {
        return 'Gambar + Keterangan';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalHeading('Sisipkan gambar')
            ->modalSubmitActionLabel('Sisipkan')
            ->schema([
                FileUpload::make('berkas')
                    ->label('Gambar')
                    ->image()
                    ->disk('public')
                    ->directory('artikel/gambar')
                    ->visibility('public')
                    ->imageEditor()
                    ->maxSize(6144)
                    ->required(),

                TextInput::make('keterangan')
                    ->label('Keterangan')
                    ->maxLength(300)
                    ->placeholder('Suasana musyawarah kerja wilayah di Wamena'),

                TextInput::make('sumber')
                    ->label('Sumber foto')
                    ->maxLength(150)
                    ->placeholder('Dokumentasi DPW'),

                Select::make('lebar')
                    ->label('Lebar tampilan')
                    ->options([
                        'normal' => 'Selebar teks',
                        'lebar'  => 'Lebih lebar dari teks',
                    ])
                    ->default('normal')
                    ->required(),
            ]);
    }

    /** @param array<string, mixed> $config */
    public static function getPreviewLabel(array $config): string
    {
        return 'Gambar: ' . ($config['keterangan'] ?: 'tanpa keterangan');
    }

    /** @param array<string, mixed> $config */
    public static function toPreviewHtml(array $config): string
    {
        return static::bangun($config);
    }

    /**
     * @param array<string, mixed> $config
     * @param array<string, mixed> $data
     */
    public static function toHtml(array $config, array $data): string
    {
        return static::bangun($config);
    }

    /** @param array<string, mixed> $config */
    protected static function bangun(array $config): string
    {
        $berkas = $config['berkas'] ?? null;

        if (blank($berkas)) {
            return '';
        }

        $url        = e(Storage::disk('public')->url($berkas));
        $keterangan = trim((string) ($config['keterangan'] ?? ''));
        $sumber     = trim((string) ($config['sumber'] ?? ''));
        $kelas      = ($config['lebar'] ?? 'normal') === 'lebar'
            ? 'blok-gambar blok-gambar--lebar'
            : 'blok-gambar';

        $alt = e($keterangan);

        $caption = '';

        if ($keterangan !== '' || $sumber !== '') {
            $isi = e($keterangan);

            if ($sumber !== '') {
                $isi .= ' <span class="blok-sumber">(' . e($sumber) . ')</span>';
            }

            $caption = "<figcaption>{$isi}</figcaption>";
        }

        return <<<HTML
        <figure class="{$kelas}">
            <img src="{$url}" alt="{$alt}" loading="lazy" decoding="async">
            {$caption}
        </figure>
        HTML;
    }
}

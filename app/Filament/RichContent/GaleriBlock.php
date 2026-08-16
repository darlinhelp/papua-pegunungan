<?php

namespace App\Filament\RichContent;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Illuminate\Support\Facades\Storage;

/**
 * Beberapa foto sekaligus dalam satu kisi, tiap foto punya keterangan sendiri.
 *
 * Berguna untuk liputan kegiatan: satu artikel bisa memuat beberapa galeri
 * di antara paragraf, tanpa harus membuat album terpisah.
 */
class GaleriBlock extends RichContentCustomBlock
{
    public static function getId(): string
    {
        return 'galeri';
    }

    public static function getLabel(): string
    {
        return 'Galeri Foto';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalHeading('Sisipkan galeri foto')
            ->modalSubmitActionLabel('Sisipkan')
            ->modalWidth('3xl')
            ->schema([
                Select::make('kolom')
                    ->label('Jumlah kolom')
                    ->options([
                        '2' => '2 kolom',
                        '3' => '3 kolom',
                        '4' => '4 kolom',
                    ])
                    ->default('3')
                    ->required(),

                Repeater::make('foto')
                    ->label('Foto')
                    ->addActionLabel('Tambah foto')
                    ->minItems(1)
                    ->maxItems(24)
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state) => $state['keterangan'] ?? 'Foto')
                    ->schema([
                        FileUpload::make('berkas')
                            ->label('Berkas')
                            ->image()
                            ->disk('public')
                            ->directory('artikel/galeri')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(6144)
                            ->required(),

                        TextInput::make('keterangan')
                            ->label('Keterangan')
                            ->maxLength(200),
                    ]),
            ]);
    }

    /** @param array<string, mixed> $config */
    public static function getPreviewLabel(array $config): string
    {
        $jumlah = count($config['foto'] ?? []);

        return "Galeri: {$jumlah} foto";
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
        $foto = $config['foto'] ?? [];

        if (empty($foto)) {
            return '';
        }

        $kolom = (string) ($config['kolom'] ?? '3');
        $isi   = '';

        foreach ($foto as $item) {
            $berkas = $item['berkas'] ?? null;

            if (blank($berkas)) {
                continue;
            }

            $url        = e(Storage::disk('public')->url($berkas));
            $keterangan = trim((string) ($item['keterangan'] ?? ''));
            $alt        = e($keterangan);

            $caption = $keterangan !== ''
                ? '<figcaption>' . e($keterangan) . '</figcaption>'
                : '';

            $isi .= <<<HTML
            <figure>
                <img src="{$url}" alt="{$alt}" loading="lazy" decoding="async">
                {$caption}
            </figure>
            HTML;
        }

        if ($isi === '') {
            return '';
        }

        return <<<HTML
        <div class="blok-galeri" data-kolom="{$kolom}">
            {$isi}
        </div>
        HTML;
    }
}

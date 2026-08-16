<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HanyaAdminDanEditor;
use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use App\Support\Spanduk;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    use HanyaAdminDanEditor;

    protected static ?string $model = Banner::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Spanduk';

    protected static ?string $modelLabel = 'Spanduk';

    protected static ?string $pluralModelLabel = 'Spanduk';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Gambar')
                ->description('Dua ukuran dipakai: yang lebar untuk layar besar, yang potret untuk ponsel. Ukuran spanduk diatur di Pengaturan → Identitas Situs.')
                // Sengaja satu kolom. Di dua kolom, area unggahnya menyempit
                // sampai teks status dan tombol batal saling menimpa.
                ->schema([
                    FileUpload::make('image_path')
                        ->label('Gambar layar lebar')
                        ->image()
                        ->disk('public')
                        ->directory('spanduk')
                        ->visibility('public')
                        ->imageEditor()
                        ->maxSize(4096)
                        ->required()
                        ->panelLayout('integrated')
                        ->removeUploadedFileButtonPosition('right')
                        ->uploadProgressIndicatorPosition('center bottom')
                        ->helperText('Ukuran: ' . Spanduk::anjuran() . '. Maksimal 4 MB.'),

                    FileUpload::make('image_mobile_path')
                        ->label('Gambar ponsel')
                        ->image()
                        ->disk('public')
                        ->directory('spanduk/ponsel')
                        ->visibility('public')
                        ->imageEditor()
                        ->maxSize(4096)
                        ->panelLayout('integrated')
                        ->removeUploadedFileButtonPosition('right')
                        ->uploadProgressIndicatorPosition('center bottom')
                        ->helperText('800 × 600 px, maksimal 4 MB. Kosong = gambar lebar dipakai, akan terpotong di ponsel.'),
                ]),

            Section::make('Teks & Tautan')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->maxLength(150),

                    TextInput::make('subtitle')
                        ->label('Anak judul')
                        ->maxLength(200)
                        ->helperText('Bila diisi, teks tampil di atas gambar.'),

                    TextInput::make('link')
                        ->label('Tautan tujuan')
                        ->url()
                        ->maxLength(300)
                        ->placeholder('/kategori/kabar-dpw atau https://...')
                        ->columnSpanFull(),

                    TextInput::make('alt_text')
                        ->label('Teks alternatif')
                        ->maxLength(200)
                        ->helperText('Untuk pembaca layar. Kosong = judul yang dipakai.')
                        ->columnSpanFull(),
                ]),

            Section::make('Penayangan')
                ->columns(3)
                ->schema([
                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),

                    DateTimePicker::make('starts_at')
                        ->label('Mulai tayang')
                        ->seconds(false)
                        ->helperText('Kosong = langsung tayang.'),

                    DateTimePicker::make('ends_at')
                        ->label('Berhenti tayang')
                        ->seconds(false)
                        ->after('starts_at')
                        ->helperText('Kosong = tanpa batas.'),

                    TextInput::make('order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0)
                        ->helperText('Kecil = lebih dulu.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Gambar')
                    ->disk('public')
                    ->height(50)
                    ->width(140)
                    ->defaultImageUrl(asset('img/placeholder-spanduk.svg')),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->wrap()
                    ->description(fn (Banner $record) => $record->subtitle),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                TextColumn::make('starts_at')
                    ->label('Mulai')
                    ->date('j M Y')
                    ->placeholder('Langsung'),

                TextColumn::make('ends_at')
                    ->label('Berakhir')
                    ->date('j M Y')
                    ->placeholder('Tanpa batas'),

                TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit'   => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}

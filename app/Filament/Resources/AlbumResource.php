<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HanyaAdminDanEditor;
use App\Filament\Resources\AlbumResource\Pages;
use App\Filament\Resources\AlbumResource\RelationManagers\PhotosRelationManager;
use App\Models\Album;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AlbumResource extends Resource
{
    use HanyaAdminDanEditor;

    protected static ?string $model = Album::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static string|\UnitEnum|null $navigationGroup = 'Media';

    protected static ?string $navigationLabel = 'Album Foto';

    protected static ?string $modelLabel = 'Album';

    protected static ?string $pluralModelLabel = 'Album Foto';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->schema([

                TextInput::make('title')
                    ->label('Judul album')
                    ->required()
                    ->maxLength(200)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set, $operation) {
                        if ($operation === 'create') {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->columnSpanFull(),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(200)
                    ->columnSpanFull(),

                TextInput::make('location')
                    ->label('Lokasi')
                    ->maxLength(150)
                    ->placeholder('Wamena, Jayawijaya'),

                DateTimePicker::make('published_at')
                    ->label('Tanggal kegiatan')
                    ->seconds(false)
                    ->default(now()),

                Textarea::make('description')
                    ->label('Keterangan')
                    ->rows(3)
                    ->columnSpanFull(),

                FileUpload::make('cover_path')
                    ->label('Sampul album')
                    ->image()
                    ->disk('public')
                    ->directory('album/sampul')
                    ->visibility('public')
                    ->imageEditor()
                    ->maxSize(4096)
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadProgressIndicatorPosition('center bottom')
                    ->helperText('Maksimal 4 MB. Kosong = foto pertama album yang dipakai.')
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_path')
                    ->label('')
                    ->disk('public')
                    ->height(44)
                    ->width(66)
                    ->defaultImageUrl(asset('img/placeholder-berita.svg')),

                TextColumn::make('title')
                    ->label('Album')
                    ->searchable()
                    ->wrap()
                    ->description(fn (Album $record) => $record->location),

                TextColumn::make('photos_count')
                    ->label('Foto')
                    ->counts('photos')
                    ->badge()
                    ->color(fn (int $state) => $state > 0 ? 'success' : 'warning')
                    ->formatStateUsing(fn (int $state) => $state . ' foto'),

                TextColumn::make('published_at')
                    ->label('Tanggal')
                    ->date('j M Y')
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->recordActions([
                EditAction::make()->label('Kelola foto'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PhotosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAlbums::route('/'),
            'create' => Pages\CreateAlbum::route('/create'),
            'edit'   => Pages\EditAlbum::route('/{record}/edit'),
        ];
    }
}

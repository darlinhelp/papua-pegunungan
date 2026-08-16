<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HanyaAdminDanEditor;
use App\Filament\Resources\PublicationResource\Pages;
use App\Models\Publication;
use Filament\Actions\Action;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PublicationResource extends Resource
{
    use HanyaAdminDanEditor;

    protected static ?string $model = Publication::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static string|\UnitEnum|null $navigationGroup = 'Media';

    protected static ?string $navigationLabel = 'Publikasi';

    protected static ?string $modelLabel = 'Terbitan';

    protected static ?string $pluralModelLabel = 'Publikasi Digital';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->schema([

                TextInput::make('title')
                    ->label('Judul')
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

                TextInput::make('series')
                    ->label('Seri')
                    ->maxLength(100)
                    ->datalist(['Buletin Honai', 'Materi Kaderisasi', 'Laporan Kegiatan'])
                    ->placeholder('Buletin Honai'),

                TextInput::make('edition')
                    ->label('Edisi')
                    ->maxLength(100)
                    ->placeholder('Edisi 12 — Agustus 2026'),

                Textarea::make('description')
                    ->label('Pengantar')
                    ->rows(3)
                    ->maxLength(500)
                    ->helperText('Paragraf pembuka di halaman baca. Boleh dikosongkan.')
                    ->columnSpanFull(),

                DateTimePicker::make('published_at')
                    ->label('Tanggal terbit')
                    ->seconds(false)
                    ->default(now())
                    ->columnSpanFull(),

                FileUpload::make('cover_path')
                    ->label('Sampul')
                    ->image()
                    ->disk('public')
                    ->directory('publikasi/sampul')
                    ->visibility('public')
                    ->imageEditor()
                    ->imageEditorAspectRatios(['3:4'])
                    ->maxSize(4096)
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadProgressIndicatorPosition('center bottom')
                    ->helperText('Potret 3:4, maksimal 4 MB.'),

                FileUpload::make('file_path')
                    ->label('Berkas PDF')
                    ->disk('public')
                    ->directory('publikasi/berkas')
                    ->visibility('public')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(40960)
                    ->downloadable()
                    ->helperText('Maksimal 40 MB. Halaman-halamannya bisa dibaca langsung di situs.'),
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
                    ->height(60)
                    ->width(45)
                    ->defaultImageUrl(asset('img/placeholder-terbitan.svg')),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->wrap()
                    ->description(fn (Publication $record) => $record->edition),

                TextColumn::make('series')
                    ->label('Seri')
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),

                TextColumn::make('file_path')
                    ->label('Berkas')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => $state ? 'Ada' : 'Belum ada')
                    ->color(fn (?string $state) => $state ? 'success' : 'danger')
                    ->default(null),

                TextColumn::make('published_at')
                    ->label('Terbit')
                    ->date('j M Y')
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                SelectFilter::make('series')
                    ->label('Seri')
                    ->options(fn () => Publication::query()
                        ->whereNotNull('series')
                        ->distinct()
                        ->pluck('series', 'series')
                        ->all()),
            ])
            ->recordActions([
                Action::make('baca')
                    ->label('Lihat')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn (Publication $record) => route('publications.show', $record))
                    ->openUrlInNewTab(),

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
            'index'  => Pages\ListPublications::route('/'),
            'create' => Pages\CreatePublication::route('/create'),
            'edit'   => Pages\EditPublication::route('/{record}/edit'),
        ];
    }
}

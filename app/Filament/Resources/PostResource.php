<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\RichContent\GaleriBlock;
use App\Filament\RichContent\GambarBlock;
use App\Models\Post;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Berita & Artikel';

    protected static ?string $modelLabel = 'Tulisan';

    protected static ?string $pluralModelLabel = 'Berita & Artikel';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            // Satu kolom sampai layar besar, baru dibagi 3 di atas 1280px.
            // Tanpa ini, kolom kanan tergencet dan teksnya turun satu kata
            // per baris di layar laptop biasa.
            ->columns(['default' => 1, 'xl' => 3])
            ->components([

                // ── Isi tulisan ───────────────────────────────────────────
                Section::make('Isi Tulisan')
                    ->columnSpan(['default' => 1, 'xl' => 2])
                    ->schema([

                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            // Slug hanya diisi otomatis saat membuat baru. Kalau
                            // judul tulisan lama diubah, slug dibiarkan supaya
                            // tautan yang sudah tersebar tidak jadi rusak.
                            ->afterStateUpdated(function ($state, $set, $operation) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(200)
                            ->prefix('/berita/'),

                        Textarea::make('excerpt')
                            ->label('Ringkasan')
                            ->rows(2)
                            ->maxLength(300)
                            ->helperText('Kosongkan untuk mengambil otomatis dari awal isi.'),

                        RichEditor::make('body')
                            ->label('Isi')
                            ->required()
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'link'],
                                ['h2', 'h3', 'blockquote', 'bulletList', 'orderedList'],
                                ['attachFiles', 'undo', 'redo'],
                            ])
                            // Blok kustom: gambar berketerangan dan galeri.
                            // Muncul di panel kanan editor, bisa diseret ke
                            // posisi mana pun di antara paragraf.
                            ->customBlocks([
                                GambarBlock::class,
                                GaleriBlock::class,
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('artikel/lampiran')
                            ->fileAttachmentsVisibility('public')
                            ->extraInputAttributes(['style' => 'min-height: 30rem']),
                    ]),

                // ── Pengaturan ────────────────────────────────────────────
                Grid::make(1)
                    ->columnSpan(1)
                    ->schema([

                        Section::make('Penerbitan')->schema([

                            Select::make('category_id')
                                ->label('Kategori')
                                ->relationship('category', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                                // Kategori baru bisa dibuat langsung dari sini,
                                // tanpa keluar dari formulir tulisan.
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->label('Nama kategori')
                                        ->required()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),
                                    TextInput::make('slug')->label('Slug')->required(),
                                    Select::make('type')
                                        ->label('Tampil di blok')
                                        ->options([
                                            'berita' => 'Berita',
                                            'opini'  => 'Opini',
                                            'media'  => 'Kutipan Media',
                                        ])
                                        ->default('berita')
                                        ->required(),
                                ]),

                            DateTimePicker::make('published_at')
                                ->label('Tanggal terbit')
                                ->seconds(false)
                                ->native(false)
                                ->displayFormat('j M Y, H:i')
                                ->default(now())
                                ->helperText('Kosong = draf.'),

                            TextInput::make('author_name')
                                ->label('Penulis')
                                ->maxLength(100)
                                ->placeholder('Humas DPW'),

                            Toggle::make('is_featured')
                                ->label('Berita Pilihan')
                                ->helperText('Tampil di blok sorotan beranda.'),
                        ]),

                        Section::make('Sampul')->schema([

                            FileUpload::make('cover_path')
                                ->label('')
                                ->image()
                                ->disk('public')
                                ->directory('berita/sampul')
                                ->visibility('public')
                                ->imageEditor()
                                ->imageEditorAspectRatios(['16:9', '3:2', '1:1'])
                                ->maxSize(4096)
                                ->panelLayout('integrated')
                                ->removeUploadedFileButtonPosition('right')
                                ->uploadProgressIndicatorPosition('center bottom')
                                ->helperText('Ideal 1200 × 800 px, maksimal 4 MB.'),

                            TextInput::make('cover_caption')
                                ->label('Keterangan gambar')
                                ->maxLength(200),
                        ]),

                        Section::make('Sumber')
                            ->description('Bila tulisan ini kutipan media luar.')
                            ->collapsed()
                            ->schema([
                                TextInput::make('source_name')
                                    ->label('Nama media')
                                    ->maxLength(100)
                                    ->placeholder('Antara'),

                                TextInput::make('source_url')
                                    ->label('Tautan')
                                    ->url()
                                    ->maxLength(300),
                            ]),
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
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(70)
                    ->description(fn (Post $record) => $record->author_name ?: 'Humas DPW'),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                IconColumn::make('is_featured')
                    ->label('Pilihan')
                    ->boolean()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-minus-small')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                TextColumn::make('published_at')
                    ->label('Terbit')
                    ->dateTime('j M Y, H:i')
                    ->sortable()
                    ->placeholder('Draf')
                    // Tulisan berjadwal ditandai supaya editor tidak mengira
                    // tulisannya sudah tampil di situs.
                    ->badge()
                    ->color(fn (Post $record) => match (true) {
                        $record->published_at === null    => 'gray',
                        $record->published_at->isFuture() => 'warning',
                        default                           => 'success',
                    })
                    ->formatStateUsing(fn ($state, Post $record) => $record->published_at?->isFuture()
                        ? 'Terjadwal · ' . $state
                        : $state),

                TextColumn::make('views')
                    ->label('Dibaca')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_featured')
                    ->label('Berita Pilihan'),

                Filter::make('draf')
                    ->label('Hanya draf')
                    ->query(fn (Builder $query) => $query->whereNull('published_at')),

                Filter::make('terjadwal')
                    ->label('Hanya terjadwal')
                    ->query(fn (Builder $query) => $query->where('published_at', '>', now())),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('terbitkan')
                        ->label('Terbitkan sekarang')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['published_at' => now()]))
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('jadikan_draf')
                        ->label('Jadikan draf')
                        ->icon('heroicon-o-eye-slash')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['published_at' => null]))
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /** Kolom kategori selalu ikut dimuat supaya tabel tidak menembak query per baris.
     *  Penulis hanya melihat tulisannya sendiri di daftar — Administrator dan
     *  Editor tetap melihat semua tulisan. */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with('category');

        if (auth()->user()?->isPenulis()) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    /** Penulis hanya boleh menyunting tulisan miliknya sendiri. */
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $pengguna = auth()->user();

        if ($pengguna?->isPenulis()) {
            return $record->user_id === $pengguna->id;
        }

        return true;
    }

    /** Aturan yang sama berlaku untuk menghapus. */
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return static::canEdit($record);
    }

    public static function getNavigationBadge(): ?string
    {
        $draf = static::getModel()::whereNull('published_at')->count();

        return $draf > 0 ? (string) $draf : null;
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Tulisan yang masih draf';
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit'   => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}

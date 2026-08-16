<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HanyaAdminDanEditor;
use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use App\Support\Ikon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    use HanyaAdminDanEditor;

    protected static ?string $model = Category::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Kategori';

    protected static ?string $modelLabel = 'Kategori';

    protected static ?string $pluralModelLabel = 'Kategori';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->schema([

                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(100)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set, $operation) {
                        if ($operation === 'create') {
                            $set('slug', Str::slug($state));
                        }
                    }),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(100)
                    ->helperText('Muncul di alamat /kategori/...'),

                Select::make('icon')
                    ->label('Ikon')
                    ->options(Ikon::pilihan())
                    ->searchable()
                    ->native(false)
                    ->placeholder('Tanpa ikon')
                    ->helperText('Tampil di kepala seksi beranda dan label kategori.'),

                Select::make('type')
                    ->label('Tampil di blok beranda')
                    ->options([
                        'berita' => 'Berita — blok Kabar DPW',
                        'opini'  => 'Opini — blok Opini',
                        'media'  => 'Kutipan Media — blok Kutipan Media',
                    ])
                    ->default('berita')
                    ->required()
                    ->helperText('Menentukan blok tempat tulisan ini muncul.'),

                TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Category $record) => $record->icon
                        ? 'Ikon: ' . (Ikon::pilihan()[$record->icon] ?? $record->icon)
                        : 'Tanpa ikon'),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->color('gray')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Blok')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'berita' => 'Berita',
                        'opini'  => 'Opini',
                        'media'  => 'Kutipan Media',
                        default  => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'berita' => 'success',
                        'opini'  => 'warning',
                        'media'  => 'info',
                        default  => 'gray',
                    }),

                TextColumn::make('posts_count')
                    ->label('Jumlah tulisan')
                    ->counts('posts')
                    ->sortable(),

                TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->recordActions([
                EditAction::make(),
                // Kategori yang masih dipakai tidak boleh dihapus: menghapusnya
                // akan ikut menghapus semua tulisan di dalamnya.
                DeleteAction::make()
                    ->disabled(fn (Category $record) => $record->posts()->exists())
                    ->tooltip(fn (Category $record) => $record->posts()->exists()
                        ? 'Masih ada tulisan di kategori ini'
                        : null),
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
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}

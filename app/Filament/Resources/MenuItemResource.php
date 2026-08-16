<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HanyaAdminDanEditor;
use App\Filament\Resources\MenuItemResource\Pages;
use App\Filament\Resources\MenuItemResource\RelationManagers\ItemsRelationManager;
use App\Models\MenuItem;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Hanya menampilkan baris kolom mega menu (parent_id kosong). Tautan di
 * dalam tiap kolom dikelola lewat ItemsRelationManager, pola yang sama
 * dengan PhotosRelationManager di AlbumResource.
 */
class MenuItemResource extends Resource
{
    use HanyaAdminDanEditor;

    protected static ?string $model = MenuItem::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bars-3';

    protected static string|\UnitEnum|null $navigationGroup = 'Halaman';

    protected static ?string $navigationLabel = 'Menu Utama';

    protected static ?string $modelLabel = 'Kolom menu';

    protected static ?string $pluralModelLabel = 'Menu Utama';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('label')
                    ->label('Nama kolom')
                    ->required()
                    ->maxLength(60)
                    ->helperText('Tampil sebagai judul dropdown di menu, mis. "Seputar DPW".'),

                Toggle::make('wide')
                    ->label('Kolom lebar (dua baris)')
                    ->helperText('Aktifkan kalau isinya banyak, mis. daftar DPD kabupaten — tautannya tersusun dua baris supaya tidak terlalu panjang ke bawah.'),

                TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0)
                    ->helperText('Angka kecil tampil lebih dulu di menu.'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->topLevel()->withCount('children'))
            ->columns([
                TextColumn::make('label')
                    ->label('Nama kolom')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('wide')
                    ->label('Kolom lebar'),

                TextColumn::make('children_count')
                    ->label('Jumlah tautan')
                    ->badge()
                    ->color(fn (int $state) => $state > 0 ? 'success' : 'warning'),

                TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->recordActions([
                EditAction::make()->label('Kelola tautan'),
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
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit'   => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}

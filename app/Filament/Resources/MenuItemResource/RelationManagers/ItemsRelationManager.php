<?php

namespace App\Filament\Resources\MenuItemResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'children';

    protected static ?string $title = 'Tautan di kolom ini';

    protected static ?string $modelLabel = 'Tautan';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('label')
                ->label('Nama tautan')
                ->required()
                ->maxLength(80)
                ->columnSpanFull(),

            TextInput::make('url')
                ->label('Alamat tujuan')
                ->required()
                ->maxLength(300)
                ->placeholder('/halaman/profil-dpw  atau  route:posts.featured  atau  https://...')
                ->helperText('Awali dengan / untuk halaman situs ini, route:nama-rute untuk halaman baku (mis. route:gallery.index), atau https:// untuk tautan keluar.')
                ->columnSpanFull(),

            Toggle::make('is_external')
                ->label('Buka di tab baru')
                ->helperText('Aktifkan untuk tautan yang menuju situs lain, mis. daftar.pks.id.'),

            TextInput::make('order')
                ->label('Urutan')
                ->numeric()
                ->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                TextColumn::make('label')
                    ->label('Nama tautan')
                    ->searchable(),

                TextColumn::make('url')
                    ->label('Alamat')
                    ->color('gray')
                    ->limit(40),

                IconColumn::make('is_external')
                    ->label('Tab baru'),

                TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->headerActions([
                CreateAction::make()->label('Tambah tautan'),
            ])
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
}

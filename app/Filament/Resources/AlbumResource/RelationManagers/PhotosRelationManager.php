<?php

namespace App\Filament\Resources\AlbumResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

class PhotosRelationManager extends RelationManager
{
    protected static string $relationship = 'photos';

    protected static ?string $title = 'Foto dalam album ini';

    protected static ?string $modelLabel = 'Foto';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            FileUpload::make('path')
                ->label('Berkas foto')
                ->image()
                ->disk('public')
                ->directory('album/foto')
                ->visibility('public')
                ->imageEditor()
                ->maxSize(6144)
                ->required()
                ->columnSpanFull(),

            TextInput::make('caption')
                ->label('Keterangan')
                ->maxLength(200)
                ->columnSpanFull(),

            TextInput::make('order')
                ->label('Urutan')
                ->numeric()
                ->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('caption')
            ->columns([
                ImageColumn::make('path')
                    ->label('')
                    ->disk('public')
                    ->height(60)
                    ->width(60),

                TextInputColumn::make('caption')
                    ->label('Keterangan')
                    ->placeholder('Belum ada keterangan'),

                TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->headerActions([
                // Unggah sekaligus: satu berkas jadi satu baris foto.
                Action::make('unggahMassal')
                    ->label('Unggah banyak foto')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->schema([
                        FileUpload::make('berkas')
                            ->label('Pilih foto')
                            ->image()
                            ->multiple()
                            ->disk('public')
                            ->directory('album/foto')
                            ->visibility('public')
                            ->maxSize(6144)
                            ->maxFiles(40)
                            ->required()
                            ->helperText('Bisa pilih sampai 40 foto sekaligus.'),
                    ])
                    ->action(function (array $data): void {
                        $urutan = $this->getOwnerRecord()->photos()->max('order') ?? 0;

                        foreach ($data['berkas'] as $jalur) {
                            $this->getOwnerRecord()->photos()->create([
                                'path'  => $jalur,
                                'order' => ++$urutan,
                            ]);
                        }
                    })
                    ->successNotificationTitle('Foto berhasil diunggah'),

                CreateAction::make()->label('Tambah satu foto'),
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

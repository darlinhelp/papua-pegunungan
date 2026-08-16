<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HanyaAdminDanEditor;
use App\Filament\Resources\PageResource\Pages;
use App\Filament\RichContent\GaleriBlock;
use App\Filament\RichContent\GambarBlock;
use App\Models\Page;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    use HanyaAdminDanEditor;

    protected static ?string $model = Page::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Halaman';

    protected static ?string $navigationLabel = 'Halaman Statis';

    protected static ?string $modelLabel = 'Halaman';

    protected static ?string $pluralModelLabel = 'Halaman Statis';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make()->columns(2)->schema([

                TextInput::make('title')
                    ->label('Judul halaman')
                    ->required()
                    ->maxLength(200)
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
                    ->maxLength(200)
                    ->prefix('/halaman/'),

                Select::make('menu_group')
                    ->label('Kelompok menu')
                    ->options([
                        'Seputar DPW'       => 'Seputar DPW',
                        'Sikap & Kebijakan' => 'Sikap & Kebijakan',
                        'DPD Kabupaten'     => 'DPD Kabupaten',
                    ])
                    ->placeholder('Tidak masuk menu')
                    ->helperText('Susunan menu diatur di config/menu.php.'),

                TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_published')
                    ->label('Terbitkan')
                    ->default(true)
                    ->columnSpanFull(),
            ]),

            Section::make('Isi Halaman')->schema([
                RichEditor::make('body')
                    ->label('')
                    ->required()
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'link'],
                        ['h2', 'h3', 'blockquote', 'bulletList', 'orderedList'],
                        ['attachFiles', 'undo', 'redo'],
                    ])
                    ->customBlocks([
                        GambarBlock::class,
                        GaleriBlock::class,
                    ])
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('halaman/lampiran')
                    ->fileAttachmentsVisibility('public')
                    ->extraInputAttributes(['style' => 'min-height: 30rem']),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Alamat')
                    ->color('gray')
                    ->prefix('/halaman/')
                    ->searchable(),

                TextColumn::make('menu_group')
                    ->label('Kelompok menu')
                    ->badge()
                    ->color('info')
                    ->placeholder('Tidak di menu'),

                IconColumn::make('is_published')
                    ->label('Terbit')
                    ->boolean(),

                TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                SelectFilter::make('menu_group')
                    ->label('Kelompok menu')
                    ->options([
                        'Seputar DPW'       => 'Seputar DPW',
                        'Sikap & Kebijakan' => 'Sikap & Kebijakan',
                        'DPD Kabupaten'     => 'DPD Kabupaten',
                    ]),
            ])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn (Page $record) => route('pages.show', $record))
                    ->openUrlInNewTab()
                    ->visible(fn (Page $record) => $record->is_published),

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
            'index'  => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit'   => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}

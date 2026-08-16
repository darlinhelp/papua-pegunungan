<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HanyaAdminDanEditor;
use App\Filament\Resources\VideoResource\Pages;
use App\Models\Video;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class VideoResource extends Resource
{
    use HanyaAdminDanEditor;

    protected static ?string $model = Video::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-play-circle';

    protected static string|\UnitEnum|null $navigationGroup = 'Media';

    protected static ?string $navigationLabel = 'Video';

    protected static ?string $modelLabel = 'Video';

    protected static ?string $pluralModelLabel = 'Video';

    protected static ?int $navigationSort = 2;

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

                TextInput::make('youtube_id')
                    ->label('Tautan atau ID YouTube')
                    ->required()
                    ->maxLength(200)
                    ->placeholder('https://www.youtube.com/watch?v=xxxxxxxxxxx')
                    ->helperText('Tempel tautan lengkap — ID diambil otomatis.')
                    // Editor tidak perlu tahu mana bagian "ID": tempel apa pun,
                    // sistem yang memotongnya.
                    ->dehydrateStateUsing(function (?string $state): ?string {
                        if (blank($state)) {
                            return null;
                        }

                        $pola = '#(?:youtu\.be/|v=|/embed/|/shorts/|/live/)([A-Za-z0-9_-]{11})#';

                        return preg_match($pola, $state, $cocok) ? $cocok[1] : trim($state);
                    })
                    ->columnSpanFull(),

                DateTimePicker::make('published_at')
                    ->label('Tanggal tayang')
                    ->seconds(false)
                    ->default(now()),

                Textarea::make('description')
                    ->label('Keterangan')
                    ->rows(4)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('youtube_id')
                    ->label('')
                    ->height(50)
                    ->width(88)
                    ->getStateUsing(fn (Video $record) => $record->thumbnailUrl('mqdefault')),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->wrap()
                    ->limit(70),

                TextColumn::make('youtube_id')
                    ->label('ID YouTube')
                    ->color('gray')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('published_at')
                    ->label('Tayang')
                    ->dateTime('j M Y')
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
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
            'index'  => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit'   => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}

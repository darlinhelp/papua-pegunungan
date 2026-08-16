<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TulisanTerbaru extends TableWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Tulisan terakhir disunting';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Post::query()->with('category')->latest('updated_at'))
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->wrap()
                    ->limit(60),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),

                TextColumn::make('published_at')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state, Post $record) => match (true) {
                        $record->published_at === null    => 'Draf',
                        $record->published_at->isFuture() => 'Terjadwal',
                        default                           => 'Terbit',
                    })
                    ->color(fn ($state, Post $record) => match (true) {
                        $record->published_at === null    => 'gray',
                        $record->published_at->isFuture() => 'warning',
                        default                           => 'success',
                    }),

                TextColumn::make('updated_at')
                    ->label('Disunting')
                    ->since(),
            ])
            ->recordActions([
                Action::make('sunting')
                    ->label('Sunting')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (Post $record) => PostResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}

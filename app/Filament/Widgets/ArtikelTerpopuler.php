<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ArtikelTerpopuler extends TableWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Berita paling banyak dibaca';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Post::query()
                ->published()
                ->with('category')
                ->orderByDesc('views'))
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

                TextColumn::make('views')
                    ->label('Dibaca')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('published_at')
                    ->label('Terbit')
                    ->date('j M Y'),
            ])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Post $record) => route('posts.show', $record))
                    ->openUrlInNewTab(),

                Action::make('sunting')
                    ->label('Sunting')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (Post $record) => PostResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}

<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tulis Berita'),
        ];
    }

    /** Tab cepat supaya editor tidak perlu membuka menu saringan. */
    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua'),

            'terbit' => Tab::make('Terbit')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now())),

            'draf' => Tab::make('Draf')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('published_at')),

            'terjadwal' => Tab::make('Terjadwal')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('published_at', '>', now())),

            'pilihan' => Tab::make('Pilihan')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_featured', true)),
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Filament\RichContent\GaleriBlock;
use App\Filament\RichContent\GambarBlock;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['title', 'slug', 'body', 'menu_group', 'order', 'is_published'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Lihat catatan di Post::renderedBody(). */
    public function renderedBody(): string
    {
        return RichContentRenderer::make($this->body)
            ->customBlocks([
                GambarBlock::class,
                GaleriBlock::class,
            ])
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsVisibility('public')
            ->toHtml();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}

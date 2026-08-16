<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Filament\RichContent\GaleriBlock;
use App\Filament\RichContent\GambarBlock;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'category_id', 'user_id', 'title', 'slug', 'excerpt', 'body',
        'cover_path', 'cover_caption', 'source_name', 'source_url',
        'author_name', 'is_featured', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured'  => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Hanya tulisan yang sudah terbit dan tanggalnya tidak di masa depan. */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->whereHas('category', fn ($q) => $q->where('type', $type));
    }

    public function coverUrl(?string $fallback = null): string
    {
        return $this->cover_path
            ? asset('storage/' . $this->cover_path)
            : ($fallback ?? asset('img/placeholder-berita.svg'));
    }

    /** Ringkasan aman: pakai excerpt kalau ada, kalau tidak potong dari isi. */
    public function summary(int $length = 160): string
    {
        return Str::limit($this->excerpt ?: strip_tags($this->body), $length);
    }

    /**
     * Isi artikel siap tampil.
     *
     * Blok kustom (gambar berketerangan, galeri) disimpan sebagai simpul JSON
     * di dalam isi. Tanpa dilewatkan renderer ini, yang muncul di halaman
     * adalah potongan JSON mentah, bukan gambarnya.
     */
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

    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->body)) / 200));
    }
}

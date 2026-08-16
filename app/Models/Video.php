<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['title', 'slug', 'youtube_id', 'description', 'published_at'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Thumbnail ditarik langsung dari YouTube, tidak perlu diunggah manual. */
    public function thumbnailUrl(string $quality = 'hqdefault'): string
    {
        return "https://i.ytimg.com/vi/{$this->youtube_id}/{$quality}.jpg";
    }

    public function embedUrl(): string
    {
        return "https://www.youtube-nocookie.com/embed/{$this->youtube_id}";
    }

    public function watchUrl(): string
    {
        return "https://www.youtube.com/watch?v={$this->youtube_id}";
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'cover_path', 'location', 'published_at',
    ];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class)->orderBy('order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function coverUrl(): string
    {
        if ($this->cover_path) {
            return asset('storage/' . $this->cover_path);
        }

        return $this->photos->first()?->url() ?? asset('img/placeholder-berita.svg');
    }
}

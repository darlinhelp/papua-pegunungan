<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model
{
    protected $fillable = ['album_id', 'path', 'caption', 'order'];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function url(): string
    {
        return $this->path
            ? asset('storage/' . $this->path)
            : asset('img/placeholder-berita.svg');
    }
}

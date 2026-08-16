<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'image_path', 'image_mobile_path', 'alt_text',
        'link', 'order', 'is_active', 'starts_at', 'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at'   => 'datetime',
        ];
    }

    /** Aktif sekarang: dicentang aktif dan berada dalam rentang tayang. */
    public function scopeLive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()));
    }

    public function imageUrl(): string
    {
        return $this->image_path
            ? asset('storage/' . $this->image_path)
            : asset('img/placeholder-spanduk.svg');
    }

    /** Versi ponsel kalau ada; kalau tidak, pakai yang sama. */
    public function mobileImageUrl(): string
    {
        if ($this->image_mobile_path) {
            return asset('storage/' . $this->image_mobile_path);
        }

        // Belum ada versi potret dan gambar utamanya pun contoh -> pakai contoh potret.
        return $this->image_path
            ? $this->imageUrl()
            : asset('img/placeholder-spanduk-hp.svg');
    }

    public function altText(): string
    {
        return $this->alt_text ?: $this->title;
    }
}

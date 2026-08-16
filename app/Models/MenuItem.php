<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = ['parent_id', 'label', 'url', 'is_external', 'wide', 'order'];

    protected function casts(): array
    {
        return [
            'is_external' => 'boolean',
            'wide'        => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }

    /** Baris kolom mega menu — yang tidak punya induk. */
    public function scopeTopLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id')->orderBy('order');
    }

    /**
     * Alamat siap pakai. 'route:nama' -> URL rute bernama; selain itu
     * dipakai apa adanya. Sama seperti closure $tautan yang dulu ada
     * langsung di Blade, dipindah ke sini supaya satu-satunya tempat.
     */
    public function tautanUrl(): string
    {
        $url = $this->url ?: '#';

        if (str_starts_with($url, 'route:')) {
            return route(substr($url, 6));
        }

        return str_starts_with($url, 'http') ? $url : url($url);
    }
}

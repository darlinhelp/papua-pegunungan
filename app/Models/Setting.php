<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    /**
     * Semua pengaturan dibaca sekali lalu disimpan di cache.
     * Yang disimpan array biasa (string => string), bukan objek —
     * aman dibaca ulang oleh cache driver mana pun.
     */
    public static function values(): array
    {
        return Cache::rememberForever(
            'settings',
            fn () => static::query()->pluck('value', 'key')->all()
        );
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        return static::values()[$key] ?? $default;
    }

    public static function put(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings'));
        static::deleted(fn () => Cache::forget('settings'));
    }
}

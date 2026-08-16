<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $fillable = [
        'title', 'slug', 'series', 'edition', 'description',
        'cover_path', 'file_path', 'published_at',
    ];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function coverUrl(): string
    {
        return $this->cover_path
            ? asset('storage/' . $this->cover_path)
            : asset('img/placeholder-terbitan.svg');
    }

    public function fileUrl(): ?string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    public function punyaBerkas(): bool
    {
        return filled($this->file_path);
    }

    /** Judul lengkap: "Buletin Honai — Edisi 12" kalau keduanya terisi. */
    public function judulLengkap(): string
    {
        return $this->edition && ! str_contains($this->title, $this->edition)
            ? $this->title . ' — ' . $this->edition
            : $this->title;
    }
}

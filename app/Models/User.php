<?php

namespace App\Models;

use App\Support\Peran;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === Peran::ADMIN;
    }

    public function isEditor(): bool
    {
        return $this->role === Peran::EDITOR;
    }

    public function isPenulis(): bool
    {
        return $this->role === Peran::PENULIS;
    }

    /** Administrator dan Editor berbagi hak yang sama untuk konten selain berita. */
    public function bisaKelolaKontenLain(): bool
    {
        return $this->isAdmin() || $this->isEditor();
    }

    /**
     * Siapa yang boleh masuk panel admin.
     *
     * Sekarang: semua akun terdaftar. Situs ini tidak membuka pendaftaran
     * publik, jadi akun hanya ada kalau dibuatkan pengurus. Peran (Peran::*)
     * yang membatasi apa yang boleh dilihat/diubah SETELAH masuk — lihat
     * method canViewAny()/canEdit() dkk di tiap Resource.
     *
     * Kalau nanti pendaftaran publik dibuka, ganti isinya — misalnya
     * batasi ke domain resmi:
     *   return str_ends_with($this->email, '@papua-pegunungan.pks.id');
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}

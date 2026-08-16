<?php

namespace App\Providers\Filament;

use App\Support\Kop;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            // Menampilkan tautan "Lupa kata sandi?" di halaman masuk. Kalau
            // dipilih, Laravel mengirim tautan atur ulang lewat email —
            // butuh pengaturan SMTP yang benar di .env, lihat catatan di
            // BACA-DULU.
            ->passwordReset()
            ->profile()
            ->brandName('DPW PKS Papua Pegunungan')
            // Logo DPW ikut tampil di halaman masuk, bukan cuma di bilah
            // kepala panel — mengikuti pengaturan Logo di panel admin,
            // supaya konsisten kalau logonya diganti.
            ->brandLogo(fn () => Kop::logoUrl())
            ->brandLogoHeight('2.75rem')
            ->favicon(asset('img/favicon.svg'))
            ->colors([
                // Oranye PKS sebagai warna utama panel, hijau lembah untuk info.
                'primary' => Color::hex('#FE5000'),
                'gray'    => Color::Slate,
                'info'    => Color::hex('#0E2B24'),
            ])
            ->font('Instrument Sans')
            // Urutan grup dipaksa lewat objek NavigationGroup. Dengan daftar
            // string biasa, urutannya bisa ikut navigationSort tiap resource
            // dan jadi terbalik di sidebar.
            ->navigationGroups([
                NavigationGroup::make('Konten')->collapsible(),
                NavigationGroup::make('Media')->collapsible(),
                NavigationGroup::make('Halaman')->collapsible(),
                NavigationGroup::make('Pengaturan')->collapsible(),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources',
            )
            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages',
            )
            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets',
            )
            ->pages([
                Dashboard::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                \Filament\Http\Middleware\Authenticate::class,
            ]);
    }
}

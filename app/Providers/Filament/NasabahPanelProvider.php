<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Enums\ThemeMode;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Navigation\NavigationItem;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Nasabah\Pages\Auth\NasabahLogin;
use Filament\Http\Middleware\AuthenticateSession;
use App\Filament\Nasabah\Pages\Auth\NasabahRegister;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class NasabahPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('nasabah')
            ->path('nasabah')
            ->login(NasabahLogin::class)
            ->registration(NasabahRegister::class)
            ->colors([
                'primary' => Color::Green,
            ])
            ->spa()
            ->discoverResources(in: app_path('Filament/Nasabah/Resources'), for: 'App\Filament\Nasabah\Resources')
            ->discoverPages(in: app_path('Filament/Nasabah/Pages'), for: 'App\Filament\Nasabah\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Nasabah/Widgets'), for: 'App\Filament\Nasabah\Widgets')
            ->widgets([
                AccountWidget::class,
                // FilamentInfoWidget::class,
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
                Authenticate::class,
            ])
            ->defaultThemeMode(ThemeMode::Light)
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('images/favicon.png'))
            ->navigationItems([
                NavigationItem::make('Halaman Utama')
                    ->url('/') // redirect ke home
                    ->icon('heroicon-o-link')
                    ->group('Links') // opsional
                    ->sort(3)
                    ->openUrlInNewTab(), // kalo mau new tab
            ]);
    }
}

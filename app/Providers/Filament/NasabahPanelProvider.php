<?php

namespace App\Providers\Filament;

use App\Filament\Nasabah\Pages\Auth\NasabahLogin;
use App\Filament\Nasabah\Pages\Auth\NasabahRegister;
use App\Http\Middleware\EnsureUserIsRegularUser;
use Filament\Auth\Pages\EditProfile;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

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
            ->topNavigation()
            ->databaseNotifications()
            ->spa()
            ->profile(EditProfile::class, false)
            ->discoverResources(in: app_path('Filament/Nasabah/Resources'), for: 'App\Filament\Nasabah\Resources')
            ->discoverPages(in: app_path('Filament/Nasabah/Pages'), for: 'App\Filament\Nasabah\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Nasabah/Widgets'), for: 'App\Filament\Nasabah\Widgets')
            ->widgets([
                // AccountWidget::class,
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
                // EnsureUserIsRegularUser::class,
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
                    ->url('/')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    // ->group('Links')
                    ->sort(4),
            ]);
    }
}

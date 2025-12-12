<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Enums\ThemeMode;
use Filament\Pages\Dashboard;
use Filament\Navigation\MenuItem;
use Filament\Support\Enums\Width;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Auth\Pages\EditProfile;
use Filament\Navigation\NavigationItem;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use App\Http\Middleware\EnsureUserIsRegularUser;
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
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use App\Filament\Resources\BalanceWithdrawals\Widgets\LatestBalanceWithdrawal;

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
            // ->profile(EditProfile::class, false)
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
            ])
            ->plugin(
                FilamentEditProfilePlugin::make()
                    ->shouldRegisterNavigation(false)
                    // ->shouldShowAvatarForm(
                    //     value: true,
                    //     directory: 'avatars',
                    //     rules: 'mimes:jpeg,png|max:1024' 
                    // )
                    ->customProfileComponents([
                            \App\Livewire\CustomProfileComponent::class,
                    ])
            )
            ->userMenuItems([
                    'profile' => MenuItem::make()
                        ->label(fn() => auth()->user()->name)
                        ->url(fn(): string => EditProfilePage::getUrl())
                        ->icon('heroicon-m-user-circle'),
                ]);
    }
}
